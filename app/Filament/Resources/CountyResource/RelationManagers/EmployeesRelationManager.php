<?php

namespace App\Filament\Resources\CountyResource\RelationManagers;

use App\Models\City;
use App\Models\County;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Illuminate\Support\Collection;
use Filament\Forms\Set;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Indicator;
use Illuminate\Support\Carbon;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make('User Location')
            ->description('Put ther user location details here.')
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->searchable(true)
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('city_id', null);
                        $set('county_id', null);
                    })
                    ->required(),
                Forms\Components\Select::make('county_id')
                    ->options(fn (Get $get): Collection => County::query()
                        ->where('country_id', $get('country_id'))
                        ->pluck('name', 'id')
                        ->prepend('Select a county', '')
                    )
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('city_id', null))
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->options(fn (Get $get): Collection => City::query()
                        ->where('county_id', $get('county_id'))
                        ->pluck('name', 'id')
                        ->prepend('Select a city', '')
                    )
                ->searchable()
                ->required(),
                Forms\Components\Select::make('department_id')
                    ->relationship(name: 'department', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ])->columns(2),
            Forms\Components\Section::make('User Name')
            ->description('Put ther user name details here.')
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required(),
                Forms\Components\TextInput::make('last_name')
                    ->required(),
                Forms\Components\TextInput::make('middle_name')
                    ->required(),
            ])->columns(3),
            Forms\Components\Section::make('User Address')
            ->description('Put ther user address details here.')
            ->schema([
                Forms\Components\TextInput::make('address')
                    ->required(),
                Forms\Components\TextInput::make('zip_code')
                    ->required(),
            ])->columns(2),
            Forms\Components\Section::make('Dates')
            ->description('Put ther user date details here.')
            ->schema([
                Forms\Components\DatePicker::make('date_of_birth')
                    ->maxDate('today')
                    ->native(false)
                    ->required(),
                Forms\Components\DatePicker::make('date_hired')
                    ->maxDate('today')
                    ->native(false)
                    ->required(),
            ])->columns(2)
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('country.name')
                ->label('Country Name')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('first_name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('last_name')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('middle_name')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('zip_code')
                ->searchable(),
            Tables\Columns\TextColumn::make('date_of_birth')
                ->date()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('date_hired')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('department.name')
                ->label('Department Name')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            SelectFilter::make('Department')
                ->relationship('department', 'name')
                ->searchable()
                ->preload()
                ->multiple()
                ->label('Filter by Department')
                ->indicator('Department'),
            Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from')
                        ->native(false),
                    DatePicker::make('created_until')
                        ->native(false),
                ])->Columns(2)
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
            
                    if ($data['created_from'] ?? null) {
                        $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                            ->removeField('created_from');
                    }
            
                    if ($data['created_until'] ?? null) {
                        $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                            ->removeField('created_until');
                    }
            
                    return $indicators;
                })
                ->columnSpan(2)
            ], layout: FiltersLayout::Modal)->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
