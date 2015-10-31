<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAcsNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acs_nodes', function (Blueprint $table) {
            $table->dropColumn('key');
        });
        Schema::table('acs_nodes', function (Blueprint $table) {
            $table->string('api_key')->nullable()->after('monitor_heartbeat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acs_nodes', function (Blueprint $table) {
            $table->dropColumn('api_key');
        });
        Schema::table('acs_nodes', function (Blueprint $table) {
            $table->string('key')->nullable()->after('monitor_heartbeat');
        });
    }
}
