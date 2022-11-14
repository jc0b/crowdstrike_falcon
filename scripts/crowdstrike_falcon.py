#!/usr/local/munkireport/munkireport-python2

import os
import subprocess
import sys
import plistlib
import re
import string
import datetime
import time
import json


def get_falcon_data():

    cmd = ['/Applications/Falcon.app/Contents/Resources/falconctl', 'stats', '--plist']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    try:
        crowdstrike_output_plist = plistlib.readPlistFromString(output)
    except Exception:
        print "Error loading plist from falconctl. Exiting..."
        exit(1)

    out = {}

    out['agent_id'] = crowdstrike_output_plist['agent_info']['agentID']
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
    return out


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
    plistlib.writePlist(result, output_plist)

if __name__ == "__main__":
    main()