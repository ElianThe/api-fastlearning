<?php

namespace App\Providers;

use App\Models\Card;
use App\Models\Folder;
use App\Models\Review;
use App\Models\Tag;
use App\Models\User;
use App\Policies\CardPolicy;
use App\Policies\FolderPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Card::class => CardPolicy::class,
        User::class => UserPolicy::class,
        Folder::class => FolderPolicy::class,
        Tag::class => TagPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
