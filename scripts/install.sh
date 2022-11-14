#!/bin/bash

# crowdstrike controller
CTL="${BASEURL}index.php?/module/crowdstrike_falcon/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/crowdstrike_falcon.py" -o "${MUNKIPATH}preflight.d/crowdstrike_falcon.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/crowdstrike_falcon.py"

	# Set preference to include this file in the preflight check
	setreportpref "crowdstrike_falcon" "${CACHEPATH}crowdstrike_falcon.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/crowdstrike_falcon.py"

	# Signal that we had an error
	ERR=1
fi