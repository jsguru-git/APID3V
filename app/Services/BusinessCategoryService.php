<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Category;
use App\Models\MapPreset;
use App\Repositories\BusinessesRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\BusinessService;

class BusinessCategoryService
{
	private $bizRepo;
	private $catRepo;
	private $bizServices;

	public function __construct(BusinessService $bizServices)
	{
		$this->bizServices = $bizServices;
	}

	/**
	 * @param null|string $keyword
	 * @return array
	 * @throws \Exception
	 */
	public function search(?string $keyword=''): array
	{
		if ('' == $keyword) {
		    throw new \Exception("The required text option is missing");
		}
		
		$businessesSuggestions = $this->bizServices->suggestWithCategories($keyword);

		$categories = $this->getCategoriesFromBusinesses($businessesSuggestions['suggestions']);

		$topCategories = [];
		if (!empty($categories))
		{
			arsort($categories);
			
			$topCategories = $this->buildResponseCategoriesSearch(slice(0, 3, $categories), $keyword);
		}

		return ['categories' => $topCategories, 'keyword' => $keyword];
		
	}

	private function buildResponseCategoriesSearch($topCategories, $keyword)
	{
		$responseCategories = [];
		foreach($topCategories as $cateName => $value)
		{
			array_push($responseCategories, $keyword. " in ".$cateName);
		}
		return $responseCategories;
	}

	private function getCategoriesFromBusinesses($businessesSuggestions = [])
	{
		$categories = [];
		foreach ($businessesSuggestions as $biz)
		{
			foreach ($biz['categories'] as $cate)
			{
				$index = array_search($cate['name'], $categories);
				if(!$index)
				{
					$cat = ['category_id' => $cate['category_id'], 'name' => $cate['name'], 'score' => 1];
					array_push($categories, $cat);
				}
				$categories[$index]['score'] += 1;
			}
		}
		return $categories;
	}
}