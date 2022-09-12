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
        Schema::create('streaming_sources', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('episode_id');
            $table->string('stream_title', 255);
            $table->string('stream_type', 63);
            $table->string('resulation', 15);
            $table->text('stream_url');
            $table->longText('headers')->nullable();

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
        Schema::dropIfExists('streaming_sources');
    }
};
