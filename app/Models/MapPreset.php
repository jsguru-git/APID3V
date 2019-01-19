<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class MapPreset extends Model
{
    use HasUuid;

    protected $with = [ 'categories'];
    protected $hidden = ['uuid'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories() {
        return $this->belongsToMany(Category::class, 'map_preset_categories');
    }
}
