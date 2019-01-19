<?php
/**
 * Created by Laurent Youmndingouetmoun.
 * 
 * Date: 01/11/19
 * Time: 9:50 AM
 */

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoriesRepository;

class CategoryService
{
    /**
     * @var Category
     */
    private $categoryRepository;

    /**
     * MapPresetService constructor.
     * @param MapPresetsRepository $mapPresetRepository
     */
    public function __construct(CategoriesRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Category|\Illuminate\Database\Eloquent\Builder
     */
    public function getActive()
    {
        return $this->categoryRepository->getActive();
    }
}