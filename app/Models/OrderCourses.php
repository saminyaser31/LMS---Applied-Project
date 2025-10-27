<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCourses extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'order_courses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'teacher_id',
        'course_id',
        'order_id',
        'status',
    ];

    public const STATUS_DISABLE = 0;
    public const STATUS_ENABLE  = 1;

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
