<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CrowdstrikeAddZtaScores extends Migration
{
    private $tableName = 'crowdstrike_falcon';

    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->integer('overall_zta_score')->nullable();
            $table->integer('os_zta_score')->nullable();
            $table->integer('sensor_zta_score')->nullable();

            $table->index('overall_zta_score');
            $table->index('os_zta_score');
            $table->index('sensor_zta_score');
        });
    }

    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('overall_zta_score');
            $table->dropColumn('os_zta_score');
            $table->dropColumn('sensor_zta_score');
        });
    }
}