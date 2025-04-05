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
        Schema::create('order', function (Blueprint $table) {
            $table->id('od_id');
            $table->integer('od_amount');
            $table->string('od_month', 40);
            $table->unsignedSmallInteger('od_year');
            $table->unsignedBigInteger('od_br_id')->index();
            $table->unsignedBigInteger('od_us_id')->nullable()->index();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('od_us_id')->references('us_id')->on('users')->onDelete('set null');
            $table->foreign('od_br_id')->references('br_id')->on('branch')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
