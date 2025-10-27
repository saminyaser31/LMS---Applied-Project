<?php

namespace App\Models\Teacher;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teachers extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'teachers';

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;

    public const STATUS_SELECT = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'password',
        'phone_no',
        'email',
        'bio',
        'detailed_info',
        'dob',
        'gender',
        'marital_status',
        'religion',
        'nid_no',
        'image',
        'cover_image',
        'nid_front_image',
        'nid_back_image',
        'experience',
        'intro_video',
        'address',
        'status',
        'terms_and_condition_agreement',
        'privacy_and_policy_agreement',
        'status',
        'remarks',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id','name','email','email_verified_at','approved']);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')
        ->select(['id', 'name', 'email']);
    }
}
