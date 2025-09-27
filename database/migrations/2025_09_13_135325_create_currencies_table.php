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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
             $table->string('name');
        // $table->string('symbol');
        //   $table->boolean('is_default')->default(false);
         $table->string('code', 10)->unique();
            $table->string('symbol', 8)->nullable();
            $table->decimal('rate', 18, 8)->default(1);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
