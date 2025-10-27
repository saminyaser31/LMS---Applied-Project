<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_profile_id');
            $table->string('media_type'); // photo, certificate, etc.
            $table->string('file_path');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('teacher_profile_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_media');
    }
}
