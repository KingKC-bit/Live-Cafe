<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            /*
             * Your database design specifies this as an ENUM,
             * but does not define the allowed values.
             * It is therefore kept as a string until those
             * values are formally decided.
             */

            $table->decimal('total', 10, 2);

            $table->dateTime('collection_time');

            $table->timestamps();

            $table->enum('status', ['pending', 'confirmed', 'collected', 'cancelled'])->default('pending');
            $table->index('collection_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
