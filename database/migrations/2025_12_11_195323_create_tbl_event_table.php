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
        Schema::create('Tbl_Event', function (Blueprint $table) {
            $table->increments('EventId');                   // Primary key
            $table->string('EventCode')->unique();           // Unique event code
            $table->unsignedInteger('EventTypeId');         // Foreign key to Tbl_EventType
            $table->unsignedInteger('VenueId');             // Foreign key to Tbl_Venue
            $table->string('EventName');                     // Event name
            $table->dateTime('StartDate')->nullable();       // Start date, nullable to avoid invalid default
            $table->dateTime('EndDate')->nullable();         // End date, nullable
            $table->boolean('IsActive')->default(true);      // Active flag
            $table->tinyInteger('EventStatus')->default(0);  // Status (0 = pending, 1 = confirmed, etc.)
            $table->integer('TotalTicketQuantity')->default(0);
            $table->integer('SoldOutTicketQuantity')->default(0);
            $table->string('CreatedBy')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->dateTime('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);

            // Foreign key constraints
            $table->foreign('EventTypeId')
                ->references('EventTypeId')->on('Tbl_EventType')
                ->onUpdate('cascade');
            //   ->onDelete('restrict');  // Prevent deletion if event type is in use

            $table->foreign('VenueId')
                ->references('VenueId')->on('Tbl_Venue')
                ->onUpdate('cascade');
            //   ->onDelete('restrict');  // Prevent deletion if venue is in use
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_Event');
    }
};