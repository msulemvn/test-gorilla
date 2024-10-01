<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'mcq' => json_encode([
                'question' => $this->faker->sentence,
                'options' => [
                    'a' => $this->faker->word,
                    'b' => $this->faker->word,
                    'c' => $this->faker->word,
                    'd' => $this->faker->word
                ]
            ]),
            'ans' => $this->faker->randomElement(['a', 'b', 'c', 'd'])
        ];
    }
}
