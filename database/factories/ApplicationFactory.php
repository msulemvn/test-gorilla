<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;



/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $personName = $this->faker->name;
        $safeName = preg_replace('/[^a-zA-Z0-9]+/', '-', $personName);
        $timestamp = Carbon::now()->format('YmdHs');
        $filename = "$timestamp-$safeName.pdf";

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->unique()->numerify('###########'),
            'attachment' => $filename,
        ];
    }
}
