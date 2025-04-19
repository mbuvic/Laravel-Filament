<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\City;
use App\Models\County;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Employee Management';

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {    
        return "{$record->first_name} {$record->last_name} {$record->middle_name}";
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name',
            'last_name',
            'middle_name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $dob = Carbon::parse($record->date_of_birth)->format('M j, Y');
        $doh = Carbon::parse($record->date_hired)   ->format('M j, Y');

        return [
            'Country'       => $record->country->name,
            'Department'    => $record->department->name,
            'Date of Birth' => $dob,
            'Date Hired'    => $doh,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['country', 'department']);
    }

    protected static ?int $cachedCount = null;

    protected static function getCachedCount(): int
    {
        if (is_null(static::$cachedCount)) {
            static::$cachedCount = static::getModel()::count();
        }
    
        return static::$cachedCount;
    }
    
    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getCachedCount(), 0);
    }
    
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getCachedCount() > 500000 ? 'success' : 'warning';
    }
    

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country Name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->sortable()
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('last_name')
                    ->sortable()
                    ->searchable()
                    ->searchable(isIndividual: true),
                Tables\Columns\TextColumn::make('middle_name')
                    ->sortable()
                    ->searchable()
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->searchable(isIndividual: true)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable()
                    ->searchable(isIndividual: true),
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
                SelectFilter::make('Country')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->label('Filter by Country')
                    ->indicator('Country'),
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
                ], layout: FiltersLayout::AboveContentCollapsible)->filtersFormColumns(4)
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
                Section::make('Location Details')
                    ->schema([
                        TextEntry::make('country.name')
                            ->label('Country'),
                        TextEntry::make('county.name')
                            ->label('County'),
                        TextEntry::make('city.name')
                            ->label('City'),
                        TextEntry::make('department.name')
                            ->label('Department'),
                    ])->columns(2),
                Section::make('Employee Details')
                    ->schema([
                        TextEntry::make('first_name')
                            ->label('First Name'),
                        TextEntry::make('last_name')
                            ->label('Last Name'),
                        TextEntry::make('middle_name')
                            ->label('Middle Name'),
                        TextEntry::make('address')
                            ->label('Address'),
                        TextEntry::make('zip_code')
                            ->label('Zip Code'),
                        TextEntry::make('date_of_birth')
                            ->date()
                            ->label('Date of Birth'),
                        TextEntry::make('date_hired')
                            ->date()
                            ->label('Date Hired'),
                    ])->columns(3),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            //'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
