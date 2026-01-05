<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('Tbl_Event', function (Blueprint $table) {
            $table->increments('EventId');
            $table->string('EventCode')->unique();
            $table->unsignedInteger('EventTypeId')->nullable();
            $table->unsignedInteger('VenueId')->nullable();
            $table->unsignedInteger('OrganizerId')->nullable();
            $table->string('EventName');
            $table->dateTime('StartDate')->nullable();
            $table->string('EventImage')->nullable();
            $table->dateTime('EndDate')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->tinyInteger('EventStatus')->default(0);
            $table->integer('TotalTicketQuantity')->default(0);
            $table->integer('SoldOutTicketQuantity')->default(0);
            $table->string('CreatedBy')->nullable();
            $table->dateTime('CreatedAt')->useCurrent();
            $table->string('ModifiedBy')->nullable();
            $table->dateTime('ModifiedAt')->nullable()->useCurrentOnUpdate();
            $table->boolean('DeleteFlag')->default(false);

            $table->foreign('EventTypeId')
                ->references('EventTypeId')->on('Tbl_EventType')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('VenueId')
                ->references('VenueId')->on('Tbl_Venue')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('OrganizerId')
                ->references('OrganizerId')->on('Tbl_EventOrganizer')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Tbl_Event');
    }
};