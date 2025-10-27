<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessages extends Model
{
    use HasFactory;

    public $table = 'contact_messages';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'subject',
        'message',
        'status',
        'updated_by',
        'deleted_by',
    ];

    public const STATUS_REPLIED = 1;
    public const STATUS_NOT_REPLIED = 2;

}
