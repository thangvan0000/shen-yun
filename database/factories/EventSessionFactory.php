<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventSession>
 */
class EventSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'venue_id'           => Venue::factory(),
            'starts_at'          => $this->faker->dateTimeBetween('now', '+3 months'),
            'capacity_total'     => $this->faker->numberBetween(50, 500),
            'capacity_reserved'  => 0,
            'registration_status' => 'open',
        ];
    }
}
