<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use ScoutElastic\Searchable;

class Category extends Model
{
    use Searchable, HasUuid;

    protected $fillable = ["name", "icon"];
    protected $hidden = ["uuid","deleted_at","created_at", "updated_at"];

    /**
     * @var \App\Elastic\Configurators\Category
     */
    protected $indexConfigurator = \App\Elastic\Configurators\Category::class;

    protected $searchRules = [
        \App\Elastic\Rules\CategorySearchRule::class
    ];

    /**
     * @var array
     */
    protected $mapping = [
        'properties' => [
            'id'                => [
                'type'  => 'integer',
                'index' => 'false'
            ],
            'name'              => [
                'type'   => 'text',
                'fields' => [
                    'english' => [
                        'type'     => 'text',
                        'analyzer' => 'english'
                    ],
                    'synonym' => [
                        'type'     => 'text',
                        'analyzer' => 'synonym_analyzer',
                        'index'    => 'true'
                    ],
                ]
            ]
        ]
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function businesses() {
        return
            $this->belongsToMany(Business::class, 'business_category', 'category_id')
                ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return
            $this->belongsToMany(User::class);
    }
}
