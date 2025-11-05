<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crud_columns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crud_table_id');
            $table->string('name');
            $table->string('type')->default('string');
            $table->string('input_type')->default('text'); // Add this line
            $table->boolean('is_relation')->default(false);
            $table->unsignedBigInteger('related_table_id')->nullable();
            $table->timestamps();

            $table->foreign('crud_table_id')
                  ->references('id')
                  ->on('crud_tables')
                  ->onDelete('cascade');

            $table->foreign('related_table_id')
                  ->references('id')
                  ->on('crud_tables')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crud_columns');
    }
};
