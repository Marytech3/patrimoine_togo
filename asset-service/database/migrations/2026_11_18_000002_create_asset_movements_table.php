<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asset_movements', function (Blueprint $table): void {
            $table->id();
            $table->string('reference', 32)->unique();
            $table->foreignId('asset_id')->nullable()->constrained()->nullOnDelete();
            $table->string('asset_reference', 32)->index();
            $table->string('type', 32)->index();
            $table->string('from_ministry', 160)->nullable();
            $table->string('to_ministry', 160)->nullable();
            $table->string('from_location', 160)->nullable();
            $table->string('to_location', 160)->nullable();
            $table->date('occurred_at')->index();
            $table->string('reason', 500);
            $table->string('recorded_by', 120)->default('Administrateur de démonstration');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_movements');
    }
};
