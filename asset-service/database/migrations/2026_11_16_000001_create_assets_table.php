<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table): void {
            $table->id();
            $table->string('reference', 32)->unique();
            $table->string('name', 160);
            $table->string('category', 80)->index();
            $table->string('type', 80)->nullable();
            $table->string('ministry', 160)->index();
            $table->string('location', 160);
            $table->string('status', 32)->default('bon')->index();
            $table->date('acquired_at')->nullable();
            $table->decimal('value_xof', 15, 0)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
