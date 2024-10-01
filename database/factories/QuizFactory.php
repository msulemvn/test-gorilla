<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Question;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $questions = Question::all()->random(10);

        $quizData = [];
        foreach ($questions as $question) {
            $mcq = json_decode($question['mcq'], true);

            $quizData['mcqs'][] = [
                'question' => $mcq['question'],
                'options' => $mcq['options'],
                'ans' => $question['ans']
            ];
        }

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph($maxSentences = 3, $maxWords = 100),
            'mcqs' => json_encode($quizData['mcqs']),
        ];
    }
}
