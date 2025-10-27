<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebImage extends Model
{
    use HasFactory;

    public $table = 'web_images';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'logo',
        'dashboard_logo',
        'favicon',
        'dashboard_favicon',
        'campaign_image',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
}
