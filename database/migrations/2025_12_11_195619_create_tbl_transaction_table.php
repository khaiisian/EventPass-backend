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
            $table->increments('TransactionId');           // Primary key
            $table->string('TransactionCode')->unique();   // Unique transaction code
            $table->unsignedInteger('UserId');            // Foreign key to Tbl_User
            $table->string('Email');                       // User email for record
            $table->boolean('Status')->default(false);     // Transaction status (paid/unpaid)
            $table->string('PaymentType')->nullable();     // Payment method
            $table->decimal('TotalAmount', 10, 2)->default(0); // Total amount
            $table->timestamp('TransactionDate')->useCurrent();
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false); // Soft delete flag

            // Foreign key constraint
            $table->foreign('UserId')->references('UserId')->on('tbl_user')
                ->onUpdate('cascade')
                ->onDelete('restrict'); // Prevent deletion if user has transactions
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