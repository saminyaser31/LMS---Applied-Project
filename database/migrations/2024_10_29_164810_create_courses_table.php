<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedInteger('subject_id')->nullable();
            $table->unsignedInteger('level_id')->nullable();
            $table->string('title');
            $table->text('short_description');
            $table->longText('long_description');
            $table->dateTime('course_start_date')->nullable();
            $table->text('requirments');
            $table->integer('total_class');
            $table->integer('certificate')->nullable();
            $table->integer('quizes')->nullable();
            $table->integer('qa')->nullable();
            $table->integer('study_tips')->nullable();
            $table->integer('career_guidance')->nullable();
            $table->integer('progress_tracking')->nullable();
            $table->integer('flex_learning_pace')->nullable();
            $table->decimal('price', 8, 2);
            $table->tinyInteger('discount_type')->nullable();
            $table->float('discount_amount')->nullable();
            $table->dateTime('discount_start_date')->nullable();
            $table->dateTime('discount_expiry_date')->nullable();

            $table->integer('duration_weeks')->nullable();
            $table->string('card_image')->nullable();
            $table->string('promotional_image')->nullable();
            $table->string('promotional_video')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('status');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('course_categories')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('course_subjects')->onDelete('cascade');
            $table->foreign('level_id')->references('id')->on('course_levels')->onDelete('cascade');

            $table->index('teacher_id');
            $table->index('category_id');
            $table->index('subject_id');
            $table->index('level_id');
            $table->index('discount_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
