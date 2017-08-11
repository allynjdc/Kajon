<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplianceMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('compliance_municipalities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location');
            $table->date('date_complied')->nullable();
            $table->integer('reminder_id');
            $table->integer('score');
            $table->integer('rejected');
            $table->integer('rejected_by')->nullable();
            $table->integer('accepted_by')->nullable();
            $table->integer('approved_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compliance_municipalities');
    }
}
