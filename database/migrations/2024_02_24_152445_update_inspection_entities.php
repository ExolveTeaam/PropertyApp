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
        Schema::table('transactions',function(Blueprint $table){
            $table->unique("transaction_reference");
        });
        Schema::table('inspectionrequests', function (Blueprint $table) {
            //
            $table->renameColumn("transaction_refernce","transaction_reference");
            $table->foreign('transaction_reference')->references('transaction_reference')->on('transactions');
            $table->foreign('inspector_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspectionrequests', function (Blueprint $table) {
            //s
            $table->dropForeign(['transaction_reference']);
            $table->dropForeign(['inspector_id']);
        });
    }
};
