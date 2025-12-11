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
        Schema::create('Tbl_VenueType', function (Blueprint $table) {
            $table->increments('VenueTypeId');           // Primary key
            $table->string('VenueTypeCode')->unique();   // Unique code
            $table->string('VenueTypeName');             // Name of the venue type
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent(); // Default current timestamp
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false); // Soft delete flag
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_VenueType');
    }
};