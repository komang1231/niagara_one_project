<?php

namespace App\Traits;

use App\Services\CodeGenerator;

trait HasGeneratedCode
{
    protected static function bootHasGeneratedCode(): void
    {
        static::creating(function ($model) {
            $field = method_exists($model, 'getCodeField') ? $model->getCodeField() : 'kode';

            if (empty($model->$field)) {
                $model->$field = CodeGenerator::generate(static::class, $model->getCodePrefix(), $field);
            }
        });
    }
}