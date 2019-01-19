<?php

namespace App\Repositories;

use App\Models\Business;
use App\Models\Category;

class CategoriesRepository
{
    /**
     * @var Category
     */
    private $model;

    /**
     * CategoryRepository constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->model = $category;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function get(int $id):Category {
        return
            $this->model->find($id);
    }

    /**
     * @param int|null $id
     * @return MapPreset|\Illuminate\Database\Eloquent\Builder
     */
    public function getActive() {
        $category = $this->model->get();

        return $category;
    }
}