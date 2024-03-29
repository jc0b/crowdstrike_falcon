<?php

use munkireport\models\MRModel as Eloquent;

class Crowdstrike_falcon_model extends Eloquent
{
    protected $table = 'crowdstrike_falcon';

    protected $fillable = [
      'serial_number',
      'agent_id',
      'customer_id',
      'sensor_operational',
      'sensor_version',
      'fulldiskaccess_granted',
      'tamper_protection',
      'overall_zta_score',
      'os_zta_score',
      'sensor_zta_score',
    ];

    public $timestamps = false;
}