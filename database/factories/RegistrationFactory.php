<?php

namespace Database\Factories;

use App\Models\EventSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'event_session_id' => EventSession::factory(),
            'full_name'        => $this->faker->name(),
            'email'            => $this->faker->safeEmail(),
            'phone'            => $this->faker->numerify('0#########'),
            'adult_count'      => $this->faker->numberBetween(0, 5),
            'ntl_count'        => $this->faker->numberBetween(0, 3),
            'ntl_new_count'    => $this->faker->numberBetween(0, 2),
            'child_count'      => $this->faker->numberBetween(0, 3),
            'total_count'      => 1,
            'attend_with_guest'=> false,
            'status'           => 'confirmed',
        ];
    }
}
