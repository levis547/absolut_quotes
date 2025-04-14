<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quote;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\TinyMce\Fields\TinyMce;
use MoonShine\UI\Components\Boolean;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<Quote>
 */
class QuotesResource extends ModelResource
{
    protected string $model = Quote::class;

    protected string $title = 'Quotes';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Цитата', 'text')->sortable(),
            Select::make('Статус', 'status')
                ->options([
                    0 => 'Не опубликован',
                    1 => 'Опубликован',
                ])
                ->sortable(),

        ];
    }

    /**
     * @return list<ComponentContract|FieldContract>
     */
    protected function formFields(): iterable
    {
        return [
            Box::make([
                ID::make(),
                TinyMce::make('Цитата', 'text')->sortable(),
                Select::make('Статус', 'status')
                    ->options([
                        0 => 'Не опубликован',
                        1 => 'Опубликован',
                    ])
                    ->sortable(),
            ])
        ];
    }

    /**
     * @return list<FieldContract>
     */
    protected function detailFields(): iterable
    {
        return [
            ID::make(),
            TinyMce::make('Цитата', 'text')->sortable(),
            Select::make('Статус', 'status')
                ->options([
                    0 => 'Не опубликован',
                    1 => 'Опубликован',
                ])
                ->sortable(),
        ];
    }

    /**
     * @param Quote $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
