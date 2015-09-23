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
            $table->string('email_public')->nullable()->after('description');
            $table->string('email_private')->nullable()->after('email_public');
            $table->string('slack_channel')->nullable()->after('email_private');
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
            $table->dropColumn('email_public');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('email_private');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('slack_channel');
        });
    }
}
