<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_storages', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->uuid('uuid')->unique();
            $table->string('service_name');
            $table->string('content_type');
            $table->string('product_id' )->nullable();            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('active_storages');
    }
};
