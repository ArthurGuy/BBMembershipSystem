<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupEmailFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('public_email')->nullable()->after('description');
            $table->string('private_email')->nullable()->after('public_email');
            $table->string('slack_channel')->nullable()->after('private_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('public_email');
            $table->dropColumn('private_email');
            $table->dropColumn('slack_channel');
        });
    }
}
