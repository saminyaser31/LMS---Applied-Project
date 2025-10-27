<?php

namespace App\Models;

use \DateTimeInterface;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{

    use HasApiTokens;
    use SoftDeletes;
    //use Auditable;
    use HasFactory;

    // Tags
    public const TAGS = [
        '0' => 'None',
        '1' => 'Abuser',
        '2' => 'Suspected'
    ];

    public const REVIEW_STATUS = [
        'BACKLOG',
        'UNDER-INVESTIGATION',
        'KYC-PENDING',
        'INVESTIGATION-COMPLETED',
        'REVISION-DECISION',
        'ON-HOLD'
    ];
    public $table = 'customers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'full_name',
        'locale',
        'account_manager_id',
        'referral_code',
        'email_verified_at',
        'phone',
        'city',
        'state',
        'address',
        'zip',
        'country_id',
        'country',
        'gender',
        'email',
        'password',
        'check_customer',
        'check_customer_by',
        'check_customer_time',
        'review_status',
        'is_eligible_cardpay',
        'created_at',
        'updated_at',
        'deleted_at',
        'try_sync_status',
        'preference',
        'otp',
        'remember_token',
        'otp_expire_date',
        'avatar',
        'mobile_country_id',
        'is_updated',
        'is_affiliate'
    ];

    protected $hidden = [
        'password','otp',
        'preference'
    ];

    protected $casts = [
        'preference' => 'array'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function isNotVerifyEmail()
    {
        return $this->email_verified_at == null;
    }
}
