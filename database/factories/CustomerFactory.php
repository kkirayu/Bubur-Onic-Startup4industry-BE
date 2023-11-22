<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'alamat' => $this->faker->address,
            'npwp' => $this->faker->numerify('##########'),
            'no_ktp' => $this->faker->numerify('##########'),
            'no_rekening' => $this->faker->numerify('##########'),
            'bank' => $this->faker->word,
            'nama_rekening' => $this->faker->name,
            'nama_pemilik_rekening' => $this->faker->name,
            'perusahaan_id' => 1,
            'cabang_id' => 1,
            //
        ];
    }
}
