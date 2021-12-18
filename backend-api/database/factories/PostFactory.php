<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(rand(5,10)),
            'body' => $this->faker->paragraphs(rand(5,10), true),
            'category' => $this->faker->jobTitle,
            'type' => rand(0,1)
        ];
    }
}
