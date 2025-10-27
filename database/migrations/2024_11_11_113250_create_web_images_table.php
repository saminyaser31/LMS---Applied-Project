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
        Schema::create('web_images', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('dashboard_logo');
            $table->string('favicon');
            $table->string('dashboard_favicon');
            $table->string('campaign_image')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_logos');
    }
};
