<?php

namespace App\Models;

use App\Models\Teacher\Teachers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherContents extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'teacher_contents';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'teacher_id',
        'content_type',
        'file_type',
        'file_path',
        'thumbnail',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public const STATUS_DISABLE = 0;
    public const STATUS_ENABLE  = 1;

    public const IMAGE = 1;
    public const VIDEO  = 2;
    public const DOCUMENTS  = 3;

    public const TYPE_FILE = 1;
    public const TYPE_URL  = 2;

    public const FILE_TYPE_SELECT = [
        TeacherContents::TYPE_FILE => 'File',
        TeacherContents::TYPE_URL => 'Url',
    ];

    public const CONTENT_TYPE_SELECT = [
        TeacherContents::IMAGE => 'Image',
        TeacherContents::VIDEO => 'Video',
        TeacherContents::DOCUMENTS => 'Document',
    ];

    public const STATUS_SELECT = [
        1 => 'Active',
        0 => 'Inactive',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teachers::class, 'teacher_id', 'user_id')
        ->select('teachers.id', 'teachers.user_id', 'teachers.first_name', 'teachers.last_name', 'teachers.email', 'teachers.image');
    }
}
