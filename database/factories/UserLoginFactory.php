<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserLogin>
 */
class UserLoginFactory extends Factory
{
    private array $randomIpAddressList = [
        "IP-1",
        "IP-2",
        "IP-3",
        "IP-4",
        "IP-5",
        "IP-6",
        "IP-7",
        "IP-8",
        "IP-9",
        "IP-10",
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ip_address' => $this->faker->randomElement($this->randomIpAddressList),
            'logged_in_at' => now(),
        ];
    }
}
