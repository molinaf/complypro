<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('endorser_id');
            $table->dateTime('endorsement_date');
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->enum('status', ['P', 'C', 'A'])->default('P'); // P = Pending, C = Completed, A = Approved and Certified
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('endorser_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
