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
        Schema::create('Tbl_TransactionTicket', function (Blueprint $table) {
            $table->increments('TransactionTicketId');          // Primary key
            $table->string('TransactionTicketCode')->unique();  // Unique ticket code
            $table->unsignedInteger('TicketTypeId');           // Foreign key to Tbl_TicketType
            $table->unsignedInteger('TransactionId');          // Foreign key to Tbl_Transaction
            $table->string('QrImage')->nullable();             // Path or URL of QR image
            $table->decimal('Price', 10, 2)->default(0);       // Ticket price
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);     // Soft delete flag

            // Foreign key constraints
            $table->foreign('TicketTypeId')->references('TicketTypeId')->on('Tbl_TicketType')
                ->onUpdate('cascade');
            // ->onDelete('restrict');  // Prevent deletion if tickets exist

            $table->foreign('TransactionId')->references('TransactionId')->on('Tbl_Transaction')
                ->onUpdate('cascade');
            // ->onDelete('restrict');  // Prevent deletion if transaction exists
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_TransactionTicket');
    }
};