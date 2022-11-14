#!/bin/bash

# Remove crowdstrike_falcon script
rm -f "${MUNKIPATH}preflight.d/crowdstrike_falcon.py"

# Remove crowdstrike_falcon.plist file
rm -f "${MUNKIPATH}preflight.d/cache/crowdstrike_falcon.plist"