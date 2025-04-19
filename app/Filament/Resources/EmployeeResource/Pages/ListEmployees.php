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

    // public function getTabs(): array
    // {

        
    //     return [
    //         //all
    //         'all'       => Tab::make('All')
    //             ->badge(Employee::query()->count()),
    //         //this week
    //         'thisWeek'  => Tab::make('This Week')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->where('date_hired', '>=', now()->subWeek())
    //             )
    //             ->badge(Employee::query()->where('date_hired', '>=', now()->subWeek())->count()),
    //         //this month
    //         'thisMonth' => Tab::make('This Month')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->where('date_hired', '>=', now()->subMonth())
    //             )
    //             ->badge(Employee::query()->where('date_hired', '>=', now()->subMonth())->count()),
    //         //this year
    //         'thisYear'  => Tab::make('This Year')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->whereBetween('date_hired', [
    //                     now()->startOfYear(),
    //                     now()->endOfYear(),
    //                 ])
    //             )
    //             ->badge(Employee::query()
    //                 ->whereBetween('date_hired', [
    //                     now()->startOfYear(),
    //                     now()->endOfYear()
    //                 ])->count()),
    //         //last year
    //         'lastYear'  => Tab::make('Last Year')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->whereBetween('date_hired', [
    //                     now()->subYear()->startOfYear(),
    //                     now()->subYear()->endOfYear(),
    //                 ])
    //             )
    //             ->badge(Employee::query()
    //                 ->whereBetween('date_hired', [
    //                     now()->subYear()->startOfYear(),
    //                     now()->subYear()->endOfYear()
    //                 ])->count()),
    //         //last 3 years
    //         'last3Years' => Tab::make('Last 3 Years')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(3)->startOfYear(),
    //                     now()->endOfYear(),
    //                 ])
    //             )
    //             ->badge(Employee::query()
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(3)->startOfYear(),
    //                     now()->endOfYear()
    //                 ])->count()),
    //         //last 5 years
    //         'last5Years' => Tab::make('Last 5 Years')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(5)->startOfYear(),
    //                     now()->endOfYear(),
    //                 ])
    //             )
    //             ->badge(Employee::query()
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(5)->startOfYear(),
    //                     now()->endOfYear()
    //                 ])->count()),
    //         //last 10 years
    //         'last10Years' => Tab::make('Last 10 Years')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(10)->startOfYear(),
    //                     now()->endOfYear(),
    //                 ])
    //             )
    //             ->badge(Employee::query()
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(10)->startOfYear(),
    //                     now()->endOfYear()
    //                 ])->count()),
    //         //last 20 years
    //         'last20Years' => Tab::make('Last 20 Years')
    //             ->modifyQueryUsing(fn (Builder $query) => $query
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(20)->startOfYear(),
    //                     now()->endOfYear(),
    //                 ])
    //             )
    //             ->badge(Employee::query()
    //                 ->whereBetween('date_hired', [
    //                     now()->subYears(20)->startOfYear(),
    //                     now()->endOfYear()
    //                 ])->count()),
    //     ];
        
    // }
    
}
