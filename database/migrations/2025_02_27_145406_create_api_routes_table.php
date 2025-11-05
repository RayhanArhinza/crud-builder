<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crud_table_id')->constrained('crud_tables')->onDelete('cascade');
            $table->string('endpoint');
            $table->string('methods');
            $table->text('description')->nullable();
            $table->string('api_token', 64);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_routes');
    }
}
