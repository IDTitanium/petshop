<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
    protected static function boot(): void
    {
        parent::boot();

        /**
         * Listen for the creating event on the model.
         * Sets the 'uuid' field value using Str::uuid() on the instance being created
         */
        static::creating(function ($model): void {
            $model->uuid = Str::uuid();
        });
    }
}
