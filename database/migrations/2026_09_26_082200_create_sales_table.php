<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Kept as string because the supplied database
             * design does not specify the permitted status values.
             */

            $table->enum('payment_method', [
                'cash',
                'card',
            ]);

            $table->decimal('total', 10, 2);

            $table->boolean('is_offline')->default(false);

            $table->dateTime('synced_at')->nullable();

            $table->dateTime('occurred_at');

            $table->timestamps();

            $table->enum('status', [ 'pending', 'confirmed', 'collected', 'cancelled', ])->default('pending');
            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};