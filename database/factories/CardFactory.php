<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\FolderTreeFolders;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $folderIds = Folder::pluck('id')->toArray();
        /*$folder_tree_folders = FolderTreeFoldersFactory::pluck('id')->toArray();*/
        return [
            'title' => fake()->title,
            'content' => fake()->words(15, true),
            'image_path' => fake()->filePath(),
            'folder_id' => fake()->randomElement($folderIds),
            'folder_tree_folders_id' => null
        ];
    }
}
