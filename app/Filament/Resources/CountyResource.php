<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\RelationManagers\CountiesRelationManager;
use App\Filament\Resources\CountyResource\Pages;
use App\Filament\Resources\CountyResource\RelationManagers;
use App\Filament\Resources\CountyResource\RelationManagers\CitiesRelationManager;
use App\Filament\Resources\CountyResource\RelationManagers\EmployeesRelationManager;
use App\Models\County;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountyResource extends Resource
{
    protected static ?string $model = County::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'County';

    protected static ?string $modelLabel = 'Employee County';

    protected static ?string $navigationGroup = 'System Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'employee-counties';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship(name: 'country', titleAttribute: 'name')
                    ->searchable(true)
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country Name')
                    ->numeric()
                    ->sortable()
                    ->searchable(isIndividual:true),
                Tables\Columns\TextColumn::make('name')
                    ->label('County Name')
                    ->searchable(isIndividual:true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('country.name', 'asc')
            ->filters([
                //filter by country
                Tables\Filters\SelectFilter::make('country')
                    ->relationship('country', 'name')
                    ->searchable(true)
                    ->preload()
                    ->label('Country'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('County Details')
                    ->schema([
                        TextEntry::make('country.name')
                            ->label('Country Name'),
                        TextEntry::make('name')
                            ->label('County Name'),
                    ])->columns(2),
                Section::make('Timestamps')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created Date'),
                        TextEntry::make('updated_at')
                            ->label('Last Modified Date'),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CitiesRelationManager::class,
            EmployeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCounties::route('/'),
            'create' => Pages\CreateCounty::route('/create'),
            'edit' => Pages\EditCounty::route('/{record}/edit'),
        ];
    }
}
