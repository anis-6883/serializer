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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();

            $table->string('app_unique_id', 127);
			$table->string('app_name', 127);
			$table->string('app_logo', 255)->default('public/default/app.png');
            $table->string('onesignal_app_id', 255)->nullable();
            $table->string('onesignal_api_key', 255)->nullable();
            $table->string('app_publishing_control', 31);
            $table->string('ads_control', 31);
            $table->string('ios_app_publishing_control', 31);
            $table->string('ios_ads_control', 31);
            $table->string('google_app_id', 127);
            $table->string('google_appOpenAd_id', 127);
            $table->string('google_banner_ads', 127);
            $table->string('google_interstitial_ads', 127);
            $table->longText('enable_countries')->nullable();
			$table->integer('status')->default(1);
            $table->enum('required_app_enable', ['Yes', 'No', 'Update'])->default('Yes');
            $table->string('required_app_id', 127);
            $table->string('required_app_url', 255);
            $table->string('required_app_name', 127);
            $table->longText('required_app_desc')->nullable();
            $table->string('required_app_logo', 255)->default('public/default/app.png');

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
        Schema::dropIfExists('apps');
    }
};
