<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_members', function (Blueprint $table) {
            $table->id();

            $table->foreignId('partnership_id')
                ->constrained('partnerships')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('employee_id', 100);

            $table->boolean('status');

            $table->timestamps();

            $table->unique([
                'partnership_id',
                'user_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_members');
    }
};
