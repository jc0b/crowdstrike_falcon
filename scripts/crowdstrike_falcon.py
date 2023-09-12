#!/usr/local/munkireport/munkireport-python3

import os
import subprocess
import sys
import plistlib
import re
import string
import datetime
import time
import json
import base64

ZTA_PATH = "/Library/Application Support/CrowdStrike/ZeroTrustAssessment/data.zta"

def open_file(path):
    try:
        f = open(path, "r")
    except IOError as e:
        print(f"WARNING: Could not open Crowdstrike ZTA file at {path}.")
        return ""
    else:
        with f:
            file = f.read()
            return file


def get_zta_data():
    zta_data = {}
    zta_file = open_file(ZTA_PATH)
    if(len(zta_file) < 1):
        print("No ZTA data was found. Perhaps the integration is not enabled?")
        return {}

    zta_score_json = zta_file.split(".")[1]
    if len(zta_score_json) % 4 == 2:
        zta_score_json += "=="
    elif len(zta_score_json) % 4 == 3:
        zta_score_json += "="
    zta_score = json.loads(base64.b64decode(zta_score_json).decode("utf-8"))

    zta_data["overall_zta_score"] = zta_score["assessment"]["overall"]
    zta_data["os_zta_score"] = zta_score["assessment"]["os"]
    zta_data["sensor_config_zta_score"] = zta_score["assessment"]["sensor_config"]

    return zta_data

def get_falcon_data():
    out = {}

    cmd = ['/Applications/Falcon.app/Contents/Resources/falconctl', 'stats', '--plist']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    try:
        crowdstrike_output_plist = plistlib.loads(output)
    except Exception as e:
        print(e)
        print("No information was loaded from Falcon. Suspected unlicensed.")
        out['customer_id'] = "Not licensed"
        out['sensor_operational'] = 'false'
        return out
    
    out['agent_id'] = crowdstrike_output_plist['agent_info']['agentID'].lower().replace('-', '')
    out['customer_id'] = crowdstrike_output_plist['agent_info']['customerID']
    out['sensor_operational'] = crowdstrike_output_plist['agent_info']['sensor_operational']
    out['sensor_version'] = crowdstrike_output_plist['agent_info']['version']
    # any of these three values being non-zero indicates that the sensor does not have FDA permissions.
    # source: https://macadmins.slack.com/archives/CA9SU2FSS/p1666719918778029?thread_ts=1666719119.280699&cid=CA9SU2FSS
    if ((int(crowdstrike_output_plist['EndpointSecurity']['auth']) + int(crowdstrike_output_plist['EndpointSecurity']['exec']) + int(crowdstrike_output_plist['EndpointSecurity']['notify']) == 0)):
        out['fulldiskaccess_granted'] = "Yes"
    else:
        out['fulldiskaccess_granted'] = "No"
    out['tamper_protection'] = crowdstrike_output_plist['dynamic_settings']['installGuard']

    zta_data = get_zta_data()

    return out | zta_data


def main():
    """Main"""

    # Check if the Falcon sensor is installed
    if not os.path.isfile('/Applications/Falcon.app/Contents/Resources/falconctl'):
        print("ERROR: Crowdstrike Falcon is not installed")
        exit(0)

    # Get information about Falcon   
    result = get_falcon_data()

    # Write results to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'crowdstrike_falcon.plist')
    try:
        plistlib.writePlist(result, output_plist)
    except:
        with open(output_plist, 'wb') as fp:
            plistlib.dump(result, fp, fmt=plistlib.FMT_XML)

if __name__ == "__main__":
    main()