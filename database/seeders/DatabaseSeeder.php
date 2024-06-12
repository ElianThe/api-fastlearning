<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Card;
use App\Models\Folder;
use App\Models\FolderTreeFolders;
use App\Models\Review;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserFolder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        $folders = Folder::factory(10)->create();
        // Attache des dossiers aléatoires à cet utilisateur
        // Utilise la méthode attach() de la relation folders() définie dans le modèle User
        $users->each(function ($user) use ($folders) {
            // Sélectionne un nombre aléatoire de dossiers (entre 1 et 3) de la collection $folders
            $user->folders()->attach(
            // Récupère uniquement les IDs des dossiers sélectionnés
            $folders->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
        FolderTreeFolders::factory(10)->create();
        $cards = Card::factory(10)->create();
        Review::factory(10)->create();
        $tags = Tag::factory(10)->create();
        $cards->each(function ($card) use ($tags){
            $card->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
