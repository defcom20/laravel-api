<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Qr;
use App\Models\QrDesign;

class QrDesignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QrDesign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url_address' => $this->faker->word,
            'dots_style' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'dots_color' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'corners_style' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'corners_color' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'background_color' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'image_file_center' => $this->faker->word,
            'qr_id' => Qr::factory(),
        ];
    }
}
