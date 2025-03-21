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
        Schema::create('type_location', function (Blueprint $table) {
            $table->id('tl_id');
            $table->string('tl_name', 255);
            $table->string('tl_emoji', 100)->nullable();
            $table->string('tl_color', 100)->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_location');
    }
};
