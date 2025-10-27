<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebTypography extends Model
{
    use HasFactory;

    public $table = 'web_typographies';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'font-family',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
}
