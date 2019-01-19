<?php

namespace App\Nova\Actions;

use App\Services\BusinessBioGeneratorService;
use Carbon\Carbon;
use Closure;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class GenerateBusinessBioAction extends Action
{

    use InteractsWithQueue, SerializesModels;

    public $name = 'Generate Bio';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $businesses
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $businesses)
    {
        foreach ($businesses as $business) {
            $business->bio = BusinessBioGeneratorService::generateBio($business);
            $business->save();
        }
    }

    public function canRun(Closure $callback)
    {
        return true;
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
