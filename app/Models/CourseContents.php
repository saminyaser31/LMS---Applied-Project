<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseContents extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'course_contents';

    public const STATUS_COMPLETE = 1;
    public const STATUS_INPROGRESS = 2;
    public const STATUS_INCOMPLETE = 0;

    public const STATUS_SELECT = [
        1 => 'Complete',
        2 => 'In Progress',
        0 => 'Incomplete',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'course_id',
        'content_no',
        'title',
        'description',
        'status',
        'class_time',
        'class_link',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
