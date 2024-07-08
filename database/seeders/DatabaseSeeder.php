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
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //faker data
        $this->fakerData();

        // JOHN PROJETS GEO :
        $this->john_account();
    }

    public function fakerData()
    {
        //USER
        $users = User::factory(10)->create();

        //FOLDER
        $folders = Folder::factory(10)->create();

        //USER FOLDER :
        $users->each(function ($user) use ($folders) {
            // Sélectionne un nombre aléatoire de dossiers (entre 1 et 3) de la collection $folders
            $user->folders()->attach(
            // Récupère uniquement les IDs des dossiers sélectionnés
                $folders->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        // CARTES
        $cards = Card::factory(10)->create();

        //REVIEWS
        $reviews = Review::factory(10)->make()->each(function ($review) use ($users, $cards) {
            $review->user_id = $users->random()->id;
            $review->card_id = collect($cards)->random()->id;
            $review->save();
        });

        // TAGS :
        $tags = Tag::factory(10)->create();
        $cards->each(function ($card) use ($tags){
            $card->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }

    public function john_account()
    {
        //USER
        $john_user = User::factory()->create([
            'email' => 'john@example.com',
            'username' => 'JohnDoe'
        ]);

        //FOLDER
        $geo_folders = Folder::factory()->create([
            'name' => 'Géographie',
            'content' => 'Dossiers de cartes géographiques',
            'created_by_user' => $john_user->id,
        ]);

        //CARTES
        $geo_cards = collect();
        $tab = [
            "France" => "Paris",
            "Italie" => "Rome",
            "Espagne" => "Madrid",
            "Allemagne" => "Berlin"
        ];
        foreach ($tab as $title => $content) {
            $geo_cards->push(Card::factory()->create([
                'title' => $title,
                'content' => $content,
                'image_path' => fake()->filePath(),
                'folder_id' => $geo_folders->id,
            ]));
        }
        // FOLDER -- USER
        $john_user->folders()->attach($geo_folders->id);

        //REVIEWS
        foreach ($geo_cards as $card) {
            $review = Review::factory()->create([
                'user_id' => $john_user->id,
                'card_id' => $card->id,
            ]);
            $review->save();
        }

        //TAGS
        $geo_tags = Tag::factory()->create([
            'name' => 'Géographie',
            'type' => 'geo'
        ]);

        $geo_cards->each(function ($card) use ($geo_tags){
            $card->tags()->attach($geo_tags->id);
        });
    }
}
