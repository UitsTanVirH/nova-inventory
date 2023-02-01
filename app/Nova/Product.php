<?php

namespace App\Nova;

use App\Nova\Filters\ProductBrand;

use App\Nova\Metrics\NewProducts;
use App\Nova\Metrics\AveragePrice;
use App\Nova\Metrics\ProductsPerDay;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public function subtitle() {
        return "Brand: {$this->brand->name}";
    }
    
    public static $globalSearchResults = 2;



    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'sku',
    ];

    public static $tableStyle = 'tight';

    public static $showColumnBorders = true;

    public static $perPageOptions = [2, 5, 10, 15];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Slug::make('Slug')
                ->from('name')
                ->required()
                ->withMeta(['extraAttributes' => [
                'readonly' => true
            ]])->hideFromIndex(),

            Text::make('Name')
                ->required()
                ->placeholder('Product name...')
                ->sortable(),

            Markdown::make('Description')
                ->required(),

            Number::make('Price')
                ->required()
                ->placeholder('Enter product price...')
                ->sortable(),

            Text::make('Sku')
                ->required()
                ->placeholder('Enter product SKU...')
                ->sortable()
                ->help('Number that retailers use to differentiate products and tract inventory level'),

            Number::make('Quantity')
                ->required()
                ->placeholder('Enter Quantity...')
                ->sortable(),

            Boolean::make('Status', 'is_published')
                ->required()
                ->sortable(),

            BelongsTo::make('Brand')
                ->sortable()
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
        return [
            new NewProducts(),
            new AveragePrice(),
            new ProductsPerDay()
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new ProductBrand()
        ];
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
        return [];
    }
}
