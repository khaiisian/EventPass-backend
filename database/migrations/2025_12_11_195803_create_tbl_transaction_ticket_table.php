<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Tbl_TransactionTicket', function (Blueprint $table) {
            $table->increments('TransactionTicketId');
            $table->string('TransactionTicketCode')->unique();
            $table->unsignedInteger('TicketTypeId')->nullable();
            $table->unsignedInteger('TransactionId')->nullable();
            $table->string('QrImage')->nullable();
            $table->decimal('Price', 10, 2)->default(0);
            $table->boolean('Status')->default(false);
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);

            $table->foreign('TicketTypeId')
                ->references('TicketTypeId')->on('Tbl_TicketType')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('TransactionId')
                ->references('TransactionId')->on('Tbl_Transaction')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Tbl_TransactionTicket');
    }
};