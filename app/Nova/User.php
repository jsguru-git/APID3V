<?php

namespace App\Nova;

use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Textarea;
class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Avatar::make('Cover Photo', 'cover_photo')
                ->hideWhenCreating()
                ->path($this->uuid),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Phone', 'phone')
                ->creationRules('unique:users,phone')
                ->updateRules('unique:users,phone,{{resourceId}}')
                ->hideFromIndex(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),

            Select::make('Gender', 'gender')
                ->options([
                      'Male'   => 'Male',
                      'Female' => 'Female',
                      'Unspecified' => 'Unspecified'
                ])
                ->hideFromIndex(),

            Select::make('Age group', 'age_group')
                ->options([
                    '20-25' => '20-25',
                    '26-30' => '26-30',
                    '31-35' => '31-35',
                    '36-40' => '36-40',
                    '41-45' => '41-45',
                    '46-50' => '46-50',
                    '51-55' => '51-55',
                    '56-60' => '56-60',
                    '61-65' => '61-65',
                    '66-70' => '66-70',
                ])
                ->hideFromIndex(),
            Textarea::make('Bio', 'bio')
                ->alwaysShow()
                ->rows(3)
                ->withMeta(['extraAttributes' => [
                    'placeholder' => 'Make it less than 140 characters',
                    'maxlength'   =>'140']
                ]),
            Boolean::make('Verified', 'verified'),
            Boolean::make('Location Tracking','allow_location_tracking')
                    ->trueValue('1')
                    ->falseValue('0')
                    ->hideFromIndex(),
            Boolean::make('Post Publicly','post_publicly')
                    ->trueValue('1')
                    ->falseValue('0')
                    ->hideFromIndex(),
            Boolean::make('T&C Agreed','t_c_agreed')
                    ->trueValue('1')
                    ->falseValue('0')
                    ->hideFromIndex(),
            Boolean::make('Profile Visible','profile_visible')
                    ->trueValue('1')
                    ->falseValue('0')
                    ->hideFromIndex(),
            HasMany::make('Reviews', 'reviews', BusinessReview::class)
                ->hideFromIndex()
                ->hideWhenUpdating()
                ->hideWhenCreating(),
            
            //BelongsToMany::make('Categories', 'categories')
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        //new Actions\UserBookmark
        return [];
    }
}
