<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'code' => $this->faker->ean13,
            'barcode' => $this->faker->ean13,
            'unit' => UnitFactory::new()->create()->id,
            'kategori' => ProductKategoriFactory::new()->create()->id,
            'brand' => BrandFactory::new()->create()->id,
            'purchase_price' => $this->faker->randomFloat(2, 1, 100),
            'selling_price' => $this->faker->randomFloat(2, 1, 200),
            'created_at' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'updated_at' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'perusahaan_id'=> 1,
            'cabang_id'=> 1,
            //
        ];
    }
}
