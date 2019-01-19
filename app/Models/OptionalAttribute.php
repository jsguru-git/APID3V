<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class OptionalAttribute extends Model
{
    use HasUuid;

    protected $fillable = ['name', 'image'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function businesses() {
        return $this->belongsToMany(Business::class)->withTimestamps();
    }
}
