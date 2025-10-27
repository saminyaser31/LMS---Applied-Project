<?php

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

function frontEndTimeConverterView($dateTime, $type = 'date-time'): ?string
{
    return $dateTime ?? "";
    $timezone  = new DateTimeZone('Etc/GMT-6'); // GMT+6:00
    $timestamp = strtotime($dateTime);
    if ($timestamp === false) {
        $datetime = new DateTime(str_replace('.', '-', $dateTime));
    } else {
        $datetime = new DateTime($dateTime);
    }
    $datetime->setTimezone($timezone);
    return $type == 'date' ? $datetime->format("Y-m-d") : $datetime->format("Y-m-d H:i:s");
}

function statusArray(): array
{
    return [
        0 => "Disabled",
        1 => "Enabled",
        2 => "Pending"
    ];
}

function setStartDateEndDate($start_date, $end_date, $format = "Y-m-d H:i:s"): array
{

    if ($start_date) {
        $date = new \DateTime($start_date);
        $start_date = $date->format($format);
    }
    if ($end_date) {
        $date = new \DateTime($end_date);
        $end_date = $date->format($format);
    }

    if ((isset($start_date) && $start_date != null) && (isset($end_date) && $end_date != null)) {
        if ($start_date > $end_date) {
            $tempDate = $start_date;
            $start_date = $end_date;
            $end_date = $tempDate;
        }
    } else if ((isset($start_date) && $start_date != null) && (isset($end_date) && $end_date == null)) {
        $start_date = $start_date;
        $end_date = $start_date;
    } else {
        $start_date = $end_date;
        $end_date = $end_date;
    }

    return ['from' => $start_date, 'to' => $end_date];
}

function generateUniqueId(): ?string
{
    $last6DigitUnique = substr(uniqid(), -5);
    $bytes            = random_bytes(4);
    return bin2hex($bytes) . $last6DigitUnique;
}

function weekName()
{
    return [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];
}

function globalDateTimeConverter($dateTime, $hasTime = true)
{
    if ($dateTime == '' || $dateTime == null) {
        return '';
    }
    if (!$hasTime) {
        return date('Y/m/d', strtotime($dateTime));
    }
    return date('Y/m/d H:i:s', strtotime($dateTime));
}

function randomNumberGenerate(): int
{
    $otp = random_int(100000, 999999);
    if (env('APP_ENV') == 'local') {
        $otp = 123456;
    }
    return $otp;
}

/**
 * @return Carbon
 */
function otpExpireDate(): Carbon
{
    return Carbon::now()->addMinutes(3);
}
