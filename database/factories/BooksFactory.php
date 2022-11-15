<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->firstName(),
            'isbn' => $this->faker->unixTime(),
            'writer' => $this->faker->monthName(),
            'genre' => $this->faker->dayOfWeek(),
            'purchase_date' => $this->faker->date(),
        ];
    }
}
