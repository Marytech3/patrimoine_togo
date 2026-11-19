<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('government_services', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 160)->unique();
            $table->string('ministry', 160)->index();
            $table->string('region', 40)->nullable()->index();
            $table->string('prefecture', 100)->nullable();
            $table->string('address', 180)->nullable();
            $table->string('manager', 120)->nullable();
            $table->string('contact', 120)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('government_services');
    }
};
