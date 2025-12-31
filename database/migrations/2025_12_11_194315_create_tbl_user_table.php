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
            $table->increments('UserId');
            $table->string('UserCode')->unique()->nullable();
            $table->string('UserName')->nullable();
            $table->string('Email')->unique()->nullable();
            $table->string('PhNumber')->nullable();
            $table->string('Password')->nullable();
            $table->string('ProfileImg')->nullable();

            $table->enum('Role', ['ADMIN', 'CUSTOMER'])->default('CUSTOMER');

            $table->string('CreatedBy')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->timestamp('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);
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