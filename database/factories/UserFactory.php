<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserState;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'usuario' => $this->faker->regexify('[A-Za-z0-9]{150}'),
            'password' => $this->faker->password,
            'type_user' => $this->faker->numberBetween(-10000, 10000),
            'potho' => $this->faker->imageUrl(640, 480, 'animals', true),
            'user_state_id' => UserState::factory(),
            'email_verified_at' => $this->faker->dateTime(),
            'remember_token' => Str::random(10),
        ];
    }
}
