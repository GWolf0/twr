<!--
users filter form
- displays a form to search/filter users records
- use the ui "/views/components/search-filters.blade.php"
-->

@php
    use App\Misc\Enums\UserRole;
    use function App\Helpers\enumOptions;

    $rolesOptions = enumOptions(UserRole::class);

    $desc = [
        'mainItem' => 'name',
        'items' => [
            [
                'main' => true,
                'name' => 'name',
                'label' => 'Name',
                'type' => 'input:text',
                'op' => 'l',
                'attrs' => 'minLength=3 maxLength=64',
            ],
            [
                'name' => 'role',
                'label' => 'Role',
                'type' => 'select',
                'options' => $rolesOptions,
                'attrs' => '',
            ],
        ],
    ];
@endphp

<x-ui.search-filters :desc="$desc" />
