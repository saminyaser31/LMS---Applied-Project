<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeContents extends Model
{
    use HasFactory;

    public $table = 'home_contents';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public const HERO_SECTION = 1;
    public const CAMPAIGN_SECTION = 2;

    protected $fillable = [
        'section_id',
        'title',
        'subtitle',
        'description',
        'image',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
