<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Qr;
use App\Models\User;

class QrFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Qr::class;

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
            'url_video' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'video_description' => $this->faker->text,
            'embed_code' => $this->faker->regexify('[A-Za-z0-9]{200}'),
            'uuid_public' => $this->faker->text,
            'uuid_visit' => $this->faker->text,
            'is_active' => $this->faker->boolean,
            'expiration_date' => $this->faker->dateTime(),
            'user_id' => User::factory(),
        ];
    }
}
