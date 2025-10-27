<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    public $table = 'contact_us';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'form_title',
        'form_subtitle',
        'form_image',
        'phone_no_1',
        'phone_no_2',
        'email_1',
        'email_2',
        'location_1',
        'location_2',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
