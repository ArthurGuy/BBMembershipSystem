<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserMandateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('mandate_id')->nullable()->after('subscription_id');
        });

        \BB\Entities\User::where('payment_method', 'gocardless-variable')->get()->each(function (\BB\Entities\User $user) {
            $user->mandate_id = $user->subscription_id;
            $user->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mandate_id']);
        });
    }
}
