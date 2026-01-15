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
            $table->increments('OrganizerId');
            $table->string('OrganizerCode')->unique()->nullable();
            $table->string('OrganizerName');
            $table->string('Email')->unique()->nullable();
            $table->string('PhNumber')->nullable();
            $table->string('Address')->nullable();
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
        Schema::dropIfExists('Tbl_EventOrganizer');
    }
};