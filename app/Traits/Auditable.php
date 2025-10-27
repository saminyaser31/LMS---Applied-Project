<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            self::audit('audit:created', $model);
        });



        static::updated(function (Model $model) {

            $model->attributes = array_merge($model->getChanges(), ['id' => $model->id]);

            self::audit('audit:updated', $model);
        });

        static::deleted(function (Model $model) {
            self::audit('audit:deleted', $model);
        });
    }

    public static function audit($description, $model)
    {
        $comment = $model->properties ? $model->properties->comment ?? null : null;
        // Convert $model->properties to an array if it's an object
        $propertiesArray = is_object($model->properties) ? (array) $model->properties : $model->properties;
        AuditLog::create([
            'description'  => $description,
            'subject_id'   => $model->id ?? null,
            'subject_type' => $model->properties ? 'login:'.''.$model->properties->login : sprintf('%s#%s', get_class($model), $model->id) ?? null,
            'user_id'      => auth()->id() ?? null,
            'properties'   => $propertiesArray ? array_merge($propertiesArray, ['comment' => $comment]) : ['comment' => $comment],
            'host'         => request()->ip() ?? null,
        ]);
    }
    public static function auditLogEntry($description, $subjectId, $subjectType, $properties)
    {
        AuditLog::create([
            'description'  => $description,
            'subject_id'   => $subjectId ?? null,
            'subject_type' => $subjectType ?? null,
            'user_id'      => auth()->id() ?? null,
            'properties'   => $properties ?? null,
            'host'         => request()->ip() ?? null,
        ]);
    }

    /**
     * @param $description
     * @param $subjectId
     * @param $subjectType
     * @param $properties
     * @return void
     */
    public static function auditLog($description, $subjectId, $subjectType, $properties)
    {

        AuditLog::create([
            'description'  => $description,
            'subject_id'   => $subjectId ?? null,
            'subject_type' => $subjectType ?? null,
            'user_id'      => auth()->id() ?? null,
            'properties'   => $properties ?? null,
            'host'         => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}
