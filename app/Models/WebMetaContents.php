<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebMetaContents extends Model
{
    use HasFactory;

    public $table = 'web_meta_contents';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
    ];
}
