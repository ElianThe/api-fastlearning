<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\FolderTreeFolders;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FolderTreeFolders>
 */
class FolderTreeFoldersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $folderIds = Folder::pluck('id')->toArray();
        return [
            'name' => fake()->name,
            'folder_id' => fake()->randomElement($folderIds),
            'parent_id' => null,
            'type' => fake()->randomElement(['niveau', 'theme', 'categorie']),
        ];
    }
}
