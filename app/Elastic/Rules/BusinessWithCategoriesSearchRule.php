<?php

namespace App\Elastic\Rules;

use ScoutElastic\SearchRule;

class BusinessWithCategoriesSearchRule extends SearchRule
{
	/**
	 * @param string $query
	 * @return array
	 */
	public static function build(string $query): array
	{
	    return [
	        'index' => 'business',
	        'type'  => 'businesses',
	        'body'  => [
	            'suggest' => [
	                'business-suggest' => [
	                    'prefix'     => $query,
	                    'completion' => [
	                        'field'           => 'suggest',
	                        'skip_duplicates' => true,
	                        'fuzzy'           => [
	                            'fuzziness' => 2
	                        ]
	                    ]
	                ]
	            ]
	        ]
	    ];
	}
}
