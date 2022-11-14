<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CrowdstrikeFalcon extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('crowdstrike_falcon', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('agent_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('sensor_operational')->nullable();
            $table->string('sensor_version')->nullable();
            $table->string('fulldiskaccess_granted')->nullable();
            $table->string('tamper_protection')->nullable();

            $table->index('serial_number');
            $table->index('agent_id');
            $table->index('customer_id');
            $table->index('sensor_operational');
            $table->index('sensor_version');
            $table->index('fulldiskaccess_granted');
            $table->index('tamper_protection');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('crowdstrike_falcon');
    }
}