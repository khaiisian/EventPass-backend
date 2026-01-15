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
        Schema::create('Tbl_Transaction', function (Blueprint $table) {
            $table->increments('TransactionId');
            $table->string('TransactionCode')->unique();
            $table->unsignedInteger('UserId')->nullable();
            $table->string('Email');
            $table->boolean('Status')->default(false);
            $table->string('PaymentType')->nullable();
            $table->decimal('TotalAmount', 10, 2)->default(0);
            $table->timestamp('TransactionDate')->useCurrent();
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);

            // Foreign key constraint
            $table->foreign('UserId')
                ->references('UserId')->on('tbl_user')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tbl_Transaction');
    }
};