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
        Schema::create('Tbl_User', function (Blueprint $table) {
            $table->increments('UserId');           // Primary key
            $table->string('UserCode')->unique();   // Unique user code
            $table->string('UserName');
            $table->string('Email')->unique();      // Unique email
            $table->string('PhNumber')->nullable(); // Phone number can be nullable
            $table->string('Password');             // Store hashed password
            $table->string('ProfileImg')->nullable(); // Profile image path optional
            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent(); // default current timestamp
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
        Schema::dropIfExists('tbl_user');
    }
};