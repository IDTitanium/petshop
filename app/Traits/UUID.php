<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
  protected static function boot()
  {
    parent::boot();

    /**
     * Listen for the creating event on the model.
     * Sets the 'uuid' field value using Str::uuid() on the instance being created
     */
    static::creating(function ($model) {
      $model->uuid = Str::uuid();
    });
  }
}
