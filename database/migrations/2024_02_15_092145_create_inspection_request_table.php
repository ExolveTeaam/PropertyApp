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
        Schema::create('inspectionrequests', function (Blueprint $table) {
            //
            $table->id();
            $table->string('unit_name');
            $table->string('location');
            $table->integer('property_type');
            $table->string('images');
            $table->boolean('is_occupied');
            $table->string('occupants_name');
            $table->string('occupants_contact');
            $table->string('transaction_reference');
            $table->date('first_date');
            $table->date('second_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspectionrequests');
    }
};
