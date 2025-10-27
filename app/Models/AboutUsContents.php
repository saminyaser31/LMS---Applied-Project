<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUsContents extends Model
{
    use HasFactory;

    public $table = 'about_us_contents';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public const ABOUT_DESCRIPTION = 1;
    public const APPLICATION_PROCEDURE = 2;
    public const COURSE_FEATURES = 3;
    public const TEACHER_SECTION = 4;

    protected $fillable = [
        'section_id',
        'section_title',
        'section_subtitle',
        'title',
        'subtitle',
        'description',
        'image_1',
        'image_2',
        'image_3',
        'icon_image',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
