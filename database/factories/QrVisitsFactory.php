<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Qr;
use App\Models\QrVisits;

class QrVisitsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QrVisits::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid,
            'visit' => $this->faker->word,
            'so' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'contry' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'city' => $this->faker->city,
            'is_active' => $this->faker->boolean,
            'qr_id' => Qr::factory(),
        ];
    }
}
