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
            $table->increments('VenueId');                // Primary key
            $table->string('VenueCode')->unique();        // Unique code
            $table->unsignedInteger('VenueTypeId');      // Foreign key
            $table->string('VenueName');                  // Venue name
            $table->string('Description')->nullable();    // Optional description
            $table->string('Address')->nullable();        // Optional address
            $table->string('VenueImage')->nullable();     // Image path
            $table->integer('Capacity')->default(0);      // Capacity
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false); // Soft delete flag

            // Foreign key constraint
            $table->foreign('VenueTypeId')->references('VenueTypeId')->on('Tbl_VenueType')
                ->onUpdate('cascade');
            // ->onDelete('restrict');  // Prevent deletion if venue type exists
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