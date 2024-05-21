<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imagePath = public_path('images/template/users-dummy');
        $images = array_diff(scandir($imagePath), array('..', '.'));
        $randomImage = $images[array_rand($images)];

        return [
            'user_id' => null, // This will be set in the seeder
            'image' => $randomImage,
        ];
    }
}
