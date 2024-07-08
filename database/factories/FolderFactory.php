<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Folder>
 */
class FolderFactory extends Factory
{
    protected $model = Folder::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        return [
            'name' => fake()->title,
            'content' => fake()->words(5, true),
            'is_public' => fake()->boolean,
            'type' => fake()->word,
            'created_by_user' => fake()->randomElement($userIds)
        ];
    }

}
