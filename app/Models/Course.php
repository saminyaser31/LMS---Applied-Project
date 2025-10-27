<?php

namespace App\Models;

use App\Models\Teacher\Teachers;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Course extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'courses';

    public const STATUS_ENABLE = 1;
    public const STATUS_DISABLE = 0;
    public const STATUS_PENDING = 2;

    public const STATUS_SELECT = [
        self::STATUS_ENABLE => 'Active',
        self::STATUS_DISABLE => 'Inactive',
        self::STATUS_PENDING => 'Pending',
    ];

    public const TYPE_ARRAY = [
        1 => 'FLAT ($)',
        2 => 'PERCENTAGE (%)',
    ];

    protected $dates = [
        'course_start_date',
        'discount_start_date',
        'discount_expiry_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'teacher_id',
        'category_id',
        'subject_id',
        'level_id',
        'title',
        'short_description',
        'long_description',
        'course_start_date',
        'requirments',
        'price',
        'discount_type',
        'discount_amount',
        'discount_start_date',
        'discount_expiry_date',
        'total_class',
        'certificate',
        'quizes',
        'qa',
        'study_tips',
        'career_guidance',
        'progress_tracking',
        'flex_learning_pace',
        'duration_weeks',
        'card_image',
        'promotional_image',
        'promotional_video',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function getDiscountStartDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDiscountStartDateAttribute($value)
    {
        $this->attributes['discount_start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDiscountExpiryDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setDiscountExpiryDateAttribute($value)
    {
        $this->attributes['discount_expiry_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getCourseStartDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setCourseStartDateAttribute($value)
    {
        $this->attributes['course_start_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getDiscountedPriceAttribute()
    {
        $price = null;

        // Check if discount is applicable
        if ($this->discount_type && $this->discount_amount) {
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $startDate = $this->discount_start_date ? Carbon::parse($this->discount_start_date) : null;
            $expiryDate = $this->discount_expiry_date ? Carbon::parse($this->discount_expiry_date) : null;

            // Check if within discount period or if no dates are set
            $isWithinPeriod = (!$startDate && !$expiryDate) ||
                ((!$startDate || $now >= $startDate) && (!$expiryDate || $now <= $expiryDate));

            if ($isWithinPeriod) {
                // Type 1 is flat, Type 2 is percentage
                if ($this->discount_type == 1) {
                    $price = max(0, $this->price - $this->discount_amount);
                } else if ($this->discount_type == 2) {
                    $price = max(0, $this->price - ($this->price * ($this->discount_amount / 100)));
                }
            }
        }

        return $price;
    }

    public function courseTeacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id', 'user_id')
        ->select('teachers.id', 'teachers.user_id', 'teachers.first_name', 'teachers.last_name', 'teachers.email', 'teachers.image');
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function courseSubject()
    {
        return $this->belongsTo(CourseSubject::class, 'subject_id');
    }

    public function courseLevel()
    {
        return $this->belongsTo(CourseLevel::class, 'level_id');
    }

    public function courseContents()
    {
        return $this->hasMany(CourseContents::class, 'course_id');
    }

    public function courseMaterials()
    {
        return $this->hasMany(CourseMaterials::class, 'course_id');
    }

    public function courseStudents()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    // public function averageRating()
    // {
    //     // If no ratings exist, return a default value of 0
    //     return $this->courseRatings()->avg('rating') ?? 0;
    // }

    public function averageRating()
    {
        // Calculate the average rating directly using a query
        $average = DB::table('course_ratings')
            ->where('course_id', $this->id)  // Filter by the current course ID
            ->avg('rating');  // Get the average rating

        // If no ratings exist, return a default value of 0
        return $average ?? 0;
    }

    public function courseRatings()
    {
        return $this->hasMany(CourseRating::class, 'course_id');
    }

    /**
     * Get the user who created the course with specific fields.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')
        ->select(['id', 'name', 'email']);
    }

    public function courseEnrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }
}
