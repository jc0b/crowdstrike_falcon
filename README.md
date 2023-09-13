Crowdstrike Falcon module
==============

A Crowdstrike Falcon module for MunkiReport that collects output from the Falcon sensors `falconctl` binary, and submits it to MunkiReport.

This module is additionally capable of reading the Zero Trust Assessment file if it is present on the device, and parsing the scores.

## Configuration

The module uses the Agent ID of a device to provide direct links to the Crowdstrike console from Munkireport for each device. To customise the region you use, please set the variable below, so that the links are correct.

```sh
CROWDSTRIKE_REGION="falcon"
```

Possible regions and their values (defaults to `falcon`):
* US-1: `falcon`
* US-2: `falcon.us-2`
* EU-1: `falcon.eu-1`
* US-gov-1: `falcon.laggar.gcw`

## Table Schema
---
* id - increments - Incremental value used by MunkiReport
* serial_number - string - Serial number of Mac
* agent_id - string - The ID of the Falcon agent on a machine
* customer_id - string - The CCID associated with the machine
* sensor_operational - string - Information on the status of the sensor
* sensor_version - string - The Falcon sensor version
* fulldiskaccess_granted - string - Whether the Falcon Sensor actually reports having Full Disk Access permission
* tamper_protection - string - Whether the sensor reports tamper protection being enabled
* overall_zta_score - integer - The overall Zero Trust Assessment score
* os_zta_score - integer - The OS Zero Trust Assessment score
* sensor_zta_score - integer - The sensor configuration Zero Trust Assessment score