<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = rand(1,2)==1 ? "Report":"Message";

        return [
            'message' => $this->faker->paragraph(),
            'type' => $type,
            'lastModifier' => NULL,
            'status' => "Unfixed",
            'read' => False,
        ];
    }
}
