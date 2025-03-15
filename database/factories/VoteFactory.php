<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'poll_id' => Poll::factory(),
            'option_id' => Option::factory(),
            'user_id' => User::factory(),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
