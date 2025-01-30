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
        Schema::create('domain_pricings', function (Blueprint $table) {
            $table->id();
            $table->string('tld')->unique();
            $table->integer('registration_price');
            $table->integer('renewal_price')->nullable();
            $table->integer('transfer_price')->nullable();
            $table->integer('whois_privacy_price')->nullable();
            $table->integer('min_registration_years')->nullable();
            $table->integer('max_registration_years')->nullable();
            $table->boolean('is_active')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_pricings');
    }
};
