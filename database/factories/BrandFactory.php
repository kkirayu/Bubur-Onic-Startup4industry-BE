<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->unique()->randomNumber(4),
            'alamat' => $this->faker->address,
            'cp' => $this->faker->name,
            'kontak_cp' => $this->faker->phoneNumber,

            'perusahaan_id'=> 1,
            'cabang_id'=> 1,
            //
        ];
    }
}
