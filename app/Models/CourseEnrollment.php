<?php

namespace App\Models;

use App\Models\Student\Students;
use App\Models\Teacher\Teachers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseEnrollment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'course_enrollments';

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
    public const STATUS_PENDING = 2;

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id', 'user_id');
    }

    public function student()
    {
        return $this->hasOneThrough(
            Students::class, // Final related model
            Order::class,   // Intermediate model
            'id',           // Foreign key on `orders` table (from CourseEnrollment)
            'user_id',      // Foreign key on `students` table
            'order_id',     // Local key on `course_enrollments` table
            'student_id'    // Local key on `orders` table
        )
        ->select('students.id', 'students.user_id', 'students.first_name', 'students.last_name', 'students.email', 'students.phone_no');
    }
}
