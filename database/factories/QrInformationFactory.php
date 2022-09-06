<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Qr;
use App\Models\QrInformation;

class QrInformationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QrInformation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'background_panel' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'business' => $this->faker->regexify('[A-Za-z0-9]{150}'),
            'video_title' => $this->faker->regexify('[A-Za-z0-9]{200}'),
            'description' => $this->faker->text,
            'link_fb' => $this->faker->word,
            'link_tw' => $this->faker->word,
            'link_tk' => $this->faker->word,
            'welcome_screen' => $this->faker->word,
            'qr_id' => Qr::factory(),
        ];
    }
}
