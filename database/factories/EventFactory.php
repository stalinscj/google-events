<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'object_id'   => $this->faker->bothify('??#?#?##'),
            'summary'     => $this->faker->words(3, true),
            'location'    => $this->faker->address(),
            'description' => $this->faker->paragraph(),
            'start'       => [
                'dateTime' => now()->addMinute(5)->toIso8601String(),
                'timeZone' => $this->faker->timezone(),
            ],
            'end'         => [
                'dateTime' => now()->addHour()->toIso8601String(),
                'timeZone' => $this->faker->timezone(),
            ],
            'status'      => $this->faker->randomElement(['cancelled', 'tentative', 'confirmed']),
        ];
    }

    /**
     * Indicate that the model's 'status' should be cancelled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cancelled()
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'cancelled'];
        });
    }

    /**
     * Indicate that the model's 'status' should be tentative.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function tentative()
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'tentative'];
        });
    }

    /**
     * Indicate that the model's 'status' should be confirmed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'confirmed'];
        });
    }
}
