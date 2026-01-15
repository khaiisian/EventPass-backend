<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Tbl_Venue', function (Blueprint $table) {
            $table->increments('VenueId');
            $table->string('VenueCode')->unique();
            $table->unsignedInteger('VenueTypeId')->nullable();
            $table->string('VenueName');
            $table->string('Description')->nullable();
            $table->string('Address')->nullable();
            $table->string('VenueImage')->nullable();
            $table->integer('Capacity')->default(0);
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);

            $table->foreign('VenueTypeId')
                ->references('VenueTypeId')->on('Tbl_VenueType')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_Venue');
    }
};