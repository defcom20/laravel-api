<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('qr_information', function (Blueprint $table) {
            $table->id();
            $table->string('background_panel', 20)->nullable();
            $table->string('business', 150)->nullable();
            $table->string('video_title', 200)->nullable();
            $table->string('description')->nullable();
            $table->string('link_fb')->nullable();
            $table->string('link_tw')->nullable();
            $table->string('link_tk')->nullable();
            $table->string('img_welcome')->nullable();
            $table->string('public_id_img');
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
        Schema::dropIfExists('qr_information');
    }
}
