<?php

	/*
	|===============================================
	| Crowdstrike Falcon
	|===============================================
	|
	| You only need to configure your Crowdstrike
	| region in this configuration file, so that 
	| links from Munkireport to a Crowdstrike
	| host record work correctly.
	| 
	| Possible values (defaults to US-1):
	| - US-1: 		falcon
	| - US-2: 		falcon.us-2
	| - EU-1: 	  falcon.eu-1
	| - US-gov-1: falcon.laggar.gcw
	|
	*/

return [
  'crowdstrike_region' => env('CROWDSTRIKE_REGION', 'falcon')
];