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
        Schema::create('Tbl_EventOrganizer', function (Blueprint $table) {
            $table->increments('OrganizerId');           // Primary key
            $table->string('OrganizerCode')->unique()->nullable(); // Unique code for organizer
            $table->string('OrganizerName');            // Organizer name
            $table->string('Email')->unique()->nullable();       // Optional unique email
            $table->string('PhNumber')->nullable();     // Optional phone number
            $table->string('Address')->nullable();      // Optional address
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false); // Soft delete
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_EventOrganizer');
    }
};