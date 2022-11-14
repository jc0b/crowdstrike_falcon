<?php

/**
 * crowdstrike_falcon class
 *
 * @package munkireport
 * @author jc0b
 **/
class Crowdstrike_falcon_controller extends Module_controller
{

    /*** Protect methods with auth! ****/
    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    public function get_falcon_status()
    {	
	jsonView(
	    $out = Crowdstrike_falcon_model::selectRaw('sensor_operational, count(*) AS count')
		->filter()
		->groupBy('sensor_operational')
		->orderBy('count', 'desc')
		->get()
		->toArray()
	    );
    }

    /**
     * Get crowdstrike_falcon health stats
    **/
    public function get_operational_stats()
    {
        jsonView(
            Crowdstrike_falcon_model::selectRaw("COUNT(CASE WHEN `sensor_operational` = 'True' THEN 1 END) AS 'sensor_operational'")
                ->selectRaw("COUNT(CASE WHEN `sensor_operational` <> 'True' THEN 1 END) AS 'sensor_inoperational'")
                ->filter()
                ->first()
                ->toLabelCount()
        );
    }

    /**
     * Get crowdstrike_falcon information for serial_number
     *
     * @param string $serial serial number
     **/
    public function get_data($serial_number = '')
    {
        jsonView(
            Crowdstrike_falcon_model::selectRaw('sensor_version, sensor_operational, agent_id, customer_id, fulldiskaccess_granted, tamper_protection')
                ->whereSerialNumber($serial_number)
                ->filter()
                ->get()
                ->toArray()
        );
    }
    
}
