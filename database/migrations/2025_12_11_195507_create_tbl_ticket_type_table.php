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
            $table->increments('TicketTypeId');
            $table->string('TicketTypeCode')->unique();
            $table->unsignedInteger('EventId')->nullable(); // Nullable for set null
            $table->string('TicketTypeName');
            $table->decimal('Price', 10, 2)->default(0);
            $table->integer('TotalQuantity')->default(0);
            $table->integer('SoldQuantity')->default(0);
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);

            // Foreign key constraint
            $table->foreign('EventId')
                ->references('EventId')->on('Tbl_Event')
                ->onUpdate('cascade')
                ->onDelete('set null');
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