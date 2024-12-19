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
        Schema::create('users_from_csv', function (Blueprint $table) {
            $table->id();
            $table->string('card_bin')->nullable();
            $table->string('brand')->nullable();
            $table->string('issuer')->nullable();
            $table->string('type')->nullable();
            $table->string('level')->nullable();
            $table->string('iso_country')->nullable();
            $table->string('country_card_issue')->nullable();
            $table->string('iso_a3')->nullable();
            $table->string('iso_number')->nullable();
            $table->longText('www')->nullable();
            $table->string('phone')->nullable();
            $table->string('extra1')->nullable();
            $table->string('extra2')->nullable();
            $table->string('extra3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_from_csv');
    }
};
