<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicationIdToUserAuthorisationsTable extends Migration
{
    public function up()
    {
        Schema::table('user_authorisations', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->after('id');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('endorser_id'); // Move user_id to applications
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_authorisations', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropColumn('application_id');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
