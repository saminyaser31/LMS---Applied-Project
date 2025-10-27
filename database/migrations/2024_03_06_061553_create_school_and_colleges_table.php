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
        Schema::create('school_and_colleges', function (Blueprint $table) {
            $table->id();
            $table->string('institute_name');
            $table->string('eiin')->nullable();
            $table->string('institute_type');
            $table->string('division');
            $table->string('district');
            $table->string('thana')->nullable();
            $table->string('union_name')->nullable();
            $table->string('mauza_name')->nullable();
            $table->string('area_status')->nullable();
            $table->string('geographical_status')->nullable();
            $table->text('address');
            $table->string('post_office')->nullable();
            $table->string('management_type')->nullable();
            $table->string('mobile')->nullable();
            $table->string('student_type')->nullable();
            $table->string('education_level');
            $table->string('affiliation')->nullable();
            $table->string('mpo_status')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->tinyText('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_and_colleges');
    }
};
