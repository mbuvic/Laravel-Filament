<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {

        
        return [
            //all
            'all'       => Tab::make('All')
                ->badge(Employee::query()->count()),
            //this week
            'thisWeek'  => Tab::make('This Week')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('date_hired', '>=', now()->subWeek())
                )
                ->badge(Employee::query()->where('date_hired', '>=', now()->subWeek())->count()),
            //this month
            'thisMonth' => Tab::make('This Month')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('date_hired', '>=', now()->subMonth())
                )
                ->badge(Employee::query()->where('date_hired', '>=', now()->subMonth())->count()),
            //this year
            'thisYear'  => Tab::make('This Year')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereBetween('date_hired', [
                        now()->startOfYear(),
                        now()->endOfYear(),
                    ])
                )
                ->badge(Employee::query()
                    ->whereBetween('date_hired', [
                        now()->startOfYear(),
                        now()->endOfYear()
                    ])->count()),
            //last year
            'lastYear'  => Tab::make('Last Year')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereBetween('date_hired', [
                        now()->subYear()->startOfYear(),
                        now()->subYear()->endOfYear(),
                    ])
                )
                ->badge(Employee::query()
                    ->whereBetween('date_hired', [
                        now()->subYear()->startOfYear(),
                        now()->subYear()->endOfYear()
                    ])->count()),
        ];
        
    }
    
}
