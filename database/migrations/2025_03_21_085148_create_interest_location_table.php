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
        Schema::create('interest_location', function (Blueprint $table) {
            $table->id('il_id');
            $table->string('il_name', 255);
            $table->integer('il_scope');
            $table->geometry('il_longlat');
            $table->string('il_address', 255)->nullable();
            $table->unsignedBigInteger('il_us_id')->nullable()->index();
            $table->unsignedBigInteger('il_tl_id')->nullable()->index();
            $table->string('il_subdistrict', 45);
            $table->string('il_district', 45);
            $table->string('il_province', 45);
            $table->string('il_postalcode', 45);

            $table->softDeletes();
            $table->timestamps();

            $table->spatialIndex('il_longlat');
            $table->foreign('il_us_id')->references('us_id')->on('users')->onDelete('set null');
            $table->foreign('il_tl_id')->references('tl_id')->on('type_location')->onDelete('set null');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_location');
    }
};
