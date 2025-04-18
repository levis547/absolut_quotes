<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\User_visit;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\UI\Components\Layout\Box;
use MoonShine\UI\Fields\ID;
use MoonShine\Contracts\UI\FieldContract;
use MoonShine\Contracts\UI\ComponentContract;
use MoonShine\UI\Fields\Text;

/**
 * @extends ModelResource<User_visit>
 */
class VisitResource extends ModelResource
{
    protected string $model = User_visit::class;

    protected string $title = 'Visits';

    /**
     * @return list<FieldContract>
     */
    protected function indexFields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Айпи адресс', 'ip_address'),
            Text::make('Броузер', 'browser'),
            Text::make('Адресс', 'address'),
            Text::make('Время обновления', 'updated_at'),
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
                Text::make('Айпи адресс', 'ip_address'),
                Text::make('Броузер', 'browser'),
                Text::make('Адресс', 'address'),
                Text::make('Количество посещений', 'count'),
                Text::make('Время обновления', 'updated_at'),
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
            Text::make('Айпи адресс', 'ip_address'),
            Text::make('Броузер', 'browser'),
            Text::make('Адресс', 'address'),
            Text::make('Время обновления', 'updated_at'),
        ];
    }

    /**
     * @param User_visit $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }
}
