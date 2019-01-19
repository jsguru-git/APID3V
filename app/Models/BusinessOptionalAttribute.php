<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessOptionalAttribute extends Model
{
    protected $table = 'business_optional_attribute';

    protected $fillable = [
        'business_id', 'optional_attribute_id', 'description'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function optionalAttribute()
    {
        return $this->belongsTo(OptionalAttribute::class);
    }
}
