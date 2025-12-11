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
        Schema::create('Tbl_TicketType', function (Blueprint $table) {
            $table->increments('TicketTypeId');              // Primary key
            $table->string('TicketTypeCode')->unique();      // Unique ticket type code
            $table->unsignedInteger('EventId');             // Foreign key to Tbl_Event
            $table->string('TicketTypeName');               // Name of the ticket type
            $table->decimal('Price', 10, 2)->default(0);    // Price of the ticket
            $table->integer('TotalQuantity')->default(0);   // Total quantity of tickets
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);  // Soft delete flag

            // Foreign key constraint
            $table->foreign('EventId')->references('EventId')->on('Tbl_Event')
                ->onUpdate('cascade');
            // ->onDelete('restrict');  // Prevent deletion if tickets exist
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_TicketType');
    }
};