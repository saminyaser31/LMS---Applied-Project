<?php

namespace App\Models\Student;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Students extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'students';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'password',
        'phone_no',
        'email',
        'dob',
        'gender',
        'marital_status',
        'religion',
        'image',
        'address',
        'status',
        'terms_and_condition_agreement',
        'privacy_and_policy_agreement',
        'status',
        'remarks',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
