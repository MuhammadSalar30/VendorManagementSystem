<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing data to convert string values to JSON arrays
        DB::table('menu_items')->whereNotNull('size')->where('size', '!=', '')->update([
            'size' => DB::raw("JSON_ARRAY(size)")
        ]);
        
        // Convert NULL values to empty JSON array
        DB::table('menu_items')->whereNull('size')->update([
            'size' => '[]'
        ]);
        
        // Now change the column type
        Schema::table('menu_items', function (Blueprint $table) {
            $table->json('size')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('size')->nullable()->change();
        });
        
        // Convert JSON arrays back to strings (take first element if array)
        DB::table('menu_items')->whereNotNull('size')->update([
            'size' => DB::raw("JSON_UNQUOTE(JSON_EXTRACT(size, '$[0]'))")
        ]);
    }
};
