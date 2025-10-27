<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseLevel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'course_levels';

    public const STATUS_ENABLE = 1;
    public const STATUS_DISABLE = 0;

    public const STATUS_SELECT = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')
        ->select(['id', 'name', 'email']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')
        ->select(['id', 'name', 'email']);
    }
}
