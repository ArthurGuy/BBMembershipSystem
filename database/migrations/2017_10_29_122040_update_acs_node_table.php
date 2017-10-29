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
            $table->boolean('entry_device')->default(0)->after('monitor_heartbeat');
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
            $table->dropColumn(['entry_device']);
        });
    }
}
