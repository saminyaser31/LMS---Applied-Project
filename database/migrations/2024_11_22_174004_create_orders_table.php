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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            // $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->tinyInteger('order_type')->nullable();
            $table->tinyInteger('gateway')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string("tracking_no")->nullable();
            $table->string('bkash_no')->nullable();
            $table->float('total', 12, 2)->default(0);
            $table->float('discount', 12, 2)->nullable();
            $table->float('commission', 12, 2)->default(0);
            $table->float('grand_total', 12, 2)->default(0);
            $table->tinyInteger('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');

            // $table->index('course_id');
            $table->index('student_id');
            $table->index('coupon_id');
            $table->index('order_type');
            $table->index('status');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
