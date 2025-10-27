<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebColor extends Model
{
    use HasFactory;

    public $table = 'web_colors';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'primary_color',
        'secondary_color',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
}
