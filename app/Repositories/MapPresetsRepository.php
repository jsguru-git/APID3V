<?php

namespace App\Repositories;

use App\Models\Business;
use App\Models\MapPreset;

class MapPresetsRepository
{
    /**
     * @var MapPreset
     */
    private $model;

    /**
     * MapPresetRepository constructor.
     * @param MapPreset $mapPreset
     */
    public function __construct(MapPreset $mapPreset)
    {
        $this->model = $mapPreset;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get(int $id):MapPreset {
        return
            $this->model->find($id);
    }

    /**
     * @param int|null $id
     * @return MapPreset|\Illuminate\Database\Eloquent\Builder
     */
    public function getActive(?int $id = null) {
        $mapPreset = $this->model;
        if (null !== $id) {
            $mapPreset->where('id', $id);

            return $mapPreset->first();
        }

        return $mapPreset;
    }
}