<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthRequisitesTable extends Migration
{
    public function up()
    {
        Schema::create('user_auth_requisites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('authorisation_id');
            $table->string('type'); // module, f2fs, inductions, licenses
            $table->unsignedBigInteger('reference_id'); // ID of the prerequisite
            $table->string('status')->default('P'); // P = Pending, C = Completed
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_authorisation_id')
                ->references('id')
                ->on('user_authorisations')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_auth_requisites');
    }
}
