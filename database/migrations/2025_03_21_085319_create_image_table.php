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
        Schema::create('image', function (Blueprint $table) {
            $table->id('i_id');
            $table->string('i_pathUrl');
            $table->unsignedBigInteger('i_br_id')->nullable()->index();
            $table->unsignedBigInteger('i_il_id')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('i_br_id')->references('br_id')->on('branch')->onDelete('cascade');
            $table->foreign('i_il_id')->references('il_id')->on('interest_location')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image');
    }
};
