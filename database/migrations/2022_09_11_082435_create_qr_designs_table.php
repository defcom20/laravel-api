<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('qr_designs', function (Blueprint $table) {
            $table->id();
            $table->string('url_address')->nullable();
            $table->string('dots_style', 20)->nullable();
            $table->string('dots_color', 20)->nullable();
            $table->string('corners_style', 20)->nullable();
            $table->string('corners_color', 20)->nullable();
            $table->string('background_color', 20)->nullable();
            $table->string('image_file_center')->nullable();
            $table->foreignId('qr_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_designs');
    }
}
