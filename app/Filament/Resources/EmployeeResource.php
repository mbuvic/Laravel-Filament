<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\City;
use App\Models\County;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Employee Management';


    public static function form(Form $form): Form
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
                    ->required(),
                    Forms\Components\DatePicker::make('date_hired')
                    ->required(),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('county_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('middle_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
