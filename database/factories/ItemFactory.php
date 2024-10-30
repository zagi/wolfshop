<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        $names = [
            'Apple AirPods',
            'Apple iPad Air',
            'Samsung Galaxy S23',
            'Xiaomi Redmi Note 13',
            'Normal Item',
            'Custom Item',
            'Another Item'
        ];

        shuffle($names);

        return [
            'name' => $this->faker->unique()->randomElement($names),
            'quality' => $this->faker->numberBetween(0, 50),
            'sellIn' => $this->faker->numberBetween(-10, 20),
        ];
    }
}
