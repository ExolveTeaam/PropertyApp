<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id')->references('id')->on('users');
            $table->string('summary');
            $table->string('property_name');
            $table->string('images');
            $table->string('property_type');
            $table->integer('inspection_id')->references('id')->on('inspectionrequests');
            $table->integer('door_accessing_property');
            $table->integer('stairway');
            $table->integer('door_hinges');
            $table->integer('door_locks');
            $table->integer('conduit_wiring');
            $table->integer('plumbing_leakage');
            $table->integer('flooring');
            $table->integer('electrical');
            $table->integer('kitchen_sink');
            $table->integer('kitchen_slab');
            $table->integer('paintings');
            $table->integer('windows_nets');
            $table->integer('ceiling_pop');
            $table->integer('bathtubs');
            $table->integer('rooms_bedrooms_cabinet');
            $table->integer('overall');
            $table->string('input_criteria');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
