<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductKategori>
 */
class ProductKategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'deskripsi' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
            'perusahaan_id'=> 1,
            'cabang_id'=> 1,
            //
        ];
    }
}
