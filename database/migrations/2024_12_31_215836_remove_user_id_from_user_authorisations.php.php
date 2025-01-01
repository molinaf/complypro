<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserIdFromUserAuthorisations extends Migration
{
    public function up()
    {
        Schema::table('user_authorisations', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign('user_authorisations_user_id_foreign');
            // Drop the user_id column
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('user_authorisations', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
