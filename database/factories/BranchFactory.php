<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Branch::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->lexify('??'),
            'name' => $this->faker->company,
            'alamat' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'logo' => null,
            'pic_id' => null,
            'created_by' => 1,
            'created_by_name' => 'Seeder',
            'updated_by' => null,
            'updated_by_name' => null,
            'deleted_by' => null,
            'deleted_by_name' => null,
            'deleted_status' => 1,
            'deleted_at' => null,
        ];
    }
}
