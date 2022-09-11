<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('qr_visits', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 100)->unique();
            $table->string('visit')->nullable();
            $table->string('so', 100)->nullable();
            $table->string('contry', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->boolean('is_active');
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
        Schema::dropIfExists('qr_visits');
    }
}
