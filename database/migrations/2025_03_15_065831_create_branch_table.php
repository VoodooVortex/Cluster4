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
        Schema::create('branch', function (Blueprint $table) {
            $table->id('br_id');
            $table->string('br_code', 100)->unique();
            $table->string('br_name', 255);
            $table->string('br_phone', 20);
            $table->integer('br_scope');
            $table->geometry('br_longlat');
            $table->string('br_address', 255)->nullable();
            $table->unsignedBigInteger('br_us_id')->nullable()->index();
            $table->string('br_subdistrict', 45);
            $table->string('br_district', 45);
            $table->string('br_province', 45);
            $table->string('br_postalcode', 45);

            $table->softDeletes();
            $table->timestamps();

            $table->spatialIndex('br_longlat');
            $table->foreign('br_us_id')->references('us_id')->on('users')->onDelete('set null');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch');
    }
};
