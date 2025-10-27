<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('type');
            $table->string('name');
            $table->string('code');
            $table->longText('description')->nullable();
            $table->tinyInteger('coupon_type_id')->unsigned()->nullable();
            $table->datetime('start_date');
            $table->datetime('expiry_date');
            $table->integer('max_redemption')->nullable();
            $table->integer('max_redemption_per_user')->nullable();
            $table->float('amount');
            $table->tinyInteger('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('coupon_type_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
        });
    }
}
