<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\County;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "country_id"=> 113, //fake()->numberBetween(1, Country::count("id")),
            'county_id'   => function (array $attrs) {
                return County::where('country_id', $attrs['country_id'])
                             ->inRandomOrder()
                             ->value('id');
            },
            'city_id'     => function (array $attrs) {
                return City::where('county_id', $attrs['county_id'])
                           ->inRandomOrder()
                           ->value('id');
            },
            "department_id"=> fake()->numberBetween(1, Department::count("id")),
            "first_name"=> fake()->firstName(),
            "last_name"=> fake()->lastName(),
            "middle_name"=> fake()->firstName(),
            "address"=> fake()->address(),
            "zip_code"=> fake()->postcode(),
            "date_of_birth"=> fake()->date(),
            "date_hired"=> fake()->date(),
        ];
    }
}
