<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Events\CommentWritten;
use App\Listeners\InsertComment;
use App\Events\LessonWatched;
use App\Listeners\UpdateLessonWatched;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Listeners\UpdateAchievement;
use App\Listeners\UpdateBadge;
use App\Events\UserCreated;
use App\Listeners\UserCreatedListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentWritten::class => [
            InsertComment::class,
        ],
        LessonWatched::class => [
            UpdateLessonWatched::class,
        ],
        AchievementUnlocked::class => [
            UpdateAchievement::class,
        ],
        BadgeUnlocked::class => [
            UpdateBadge::class,
        ],
        UserCreated::class => [
            UserCreatedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
