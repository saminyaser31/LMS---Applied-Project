<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'student_id',
        // 'course_id',
        'coupon_id',
        'order_type',
        'gateway',
        'transaction_id',
        'tracking_no',
        'bkash_no',
        'total',
        'discount',
        'commission',
        'grand_total',
        'status',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public const STATUS_DISABLE = 0;
    public const STATUS_ENABLE  = 1;
    public const STATUS_PENDING = 2;
    public const STATUS_CANCELLED = 3;

    public const NEW_PAID_ORDER = 1;
    public const FREE_ORDER  = 2;
    public const TEST_ORDER  = 3;

    public const ORDER_TYPE_SELECT = [
        Order::NEW_PAID_ORDER => 'Paid Order',
        Order::FREE_ORDER => 'Free Order',
        Order::TEST_ORDER => 'Test Order',
    ];

    public const STATUS_SELECT = [
        Order::STATUS_ENABLE => 'Enable',
        Order::STATUS_DISABLE => 'Disable',
        Order::STATUS_PENDING => 'Pending',
        Order::STATUS_CANCELLED => 'Cancelled',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->hasManyThrough(User::class, CourseEnrollment::class, 'order_id', 'id', 'id', 'teacher_id')
        ->select('users.id', 'users.name','users.email');
    }

    public function courseEnrollment()
    {
        return $this->hasMany(CourseEnrollment::class)->with('courses:id,title');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
