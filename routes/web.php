<?php

use App\Livewire\Installer\Welcome as InstallerWelcome;
use App\Livewire\Installer\Requirements as InstallerRequirements;
use App\Livewire\Installer\Database as InstallerDatabase;
use App\Livewire\Installer\Admin as InstallerAdmin;
use App\Livewire\Installer\Site as InstallerSite;
use App\Livewire\Installer\Complete as InstallerComplete;
use App\Livewire\Profile\Show as ProfileShow;
use App\Livewire\Profile\Edit as ProfileEdit;
use App\Livewire\Connections\FriendsList;
use App\Livewire\Connections\PendingRequests;
use App\Livewire\Connections\FollowersList;
use App\Livewire\Groups\Index as GroupsIndex;
use App\Livewire\Groups\Create as GroupsCreate;
use App\Livewire\Groups\Show as GroupsShow;
use App\Livewire\Groups\Members as GroupsMembers;
use App\Livewire\Messages\Index as MessagesIndex;
use App\Livewire\Feed\Index as FeedIndex;
use App\Livewire\Media\Index as MediaIndex;
use App\Livewire\Media\AlbumShow as MediaAlbumShow;
use App\Livewire\Blog\Index as BlogIndex;
use App\Livewire\Blog\Create as BlogCreate;
use App\Livewire\Blog\Edit as BlogEdit;
use App\Livewire\Blog\Show as BlogShow;
use App\Livewire\Events\Index as EventsIndex;
use App\Livewire\Events\Create as EventsCreate;
use App\Livewire\Events\Show as EventsShow;
use App\Livewire\Notifications\NotificationList;
use App\Livewire\Onboarding\Welcome as OnboardingWelcome;
use App\Livewire\Feed\SavedPosts;
use App\Livewire\Feed\Memories;
use App\Livewire\Pages\Index as PagesIndex;
use App\Livewire\Pages\Create as PagesCreate;
use App\Livewire\Pages\Show as PagesShow;
use App\Livewire\Marketplace\Index as MarketplaceIndex;
use App\Livewire\Marketplace\Create as MarketplaceCreate;
use App\Livewire\Marketplace\Show as MarketplaceShow;
use App\Livewire\Fundraisers\Index as FundraisersIndex;
use App\Livewire\Fundraisers\Create as FundraisersCreate;
use App\Livewire\Fundraisers\Show as FundraisersShow;
use App\Livewire\Hashtags\Show as HashtagShow;
use App\Livewire\Watch\Index as WatchIndex;
use App\Livewire\Settings\CloseFriends;
use App\Livewire\Settings\RestrictedUsers;
use App\Livewire\Security\Sessions as SecuritySessions;
use App\Livewire\Collections\Index as CollectionsIndex;
use App\Livewire\Collections\Create as CollectionsCreate;
use App\Livewire\Collections\Show as CollectionsShow;
use App\Livewire\Activity\Log as ActivityLog;
use App\Livewire\Activity\PrivacyDashboard;
use App\Livewire\Pokes\Index as PokesIndex;
use App\Livewire\Questions\Index as QuestionsIndex;
use App\Livewire\Questions\Answer as QuestionsAnswer;
use App\Livewire\Boards\Index as BoardsIndex;
use App\Livewire\Boards\Create as BoardsCreate;
use App\Livewire\Boards\Show as BoardsShow;
use App\Livewire\Series\Index as SeriesIndex;
use App\Livewire\Series\Create as SeriesCreate;
use App\Livewire\Series\Show as SeriesShow;
use App\Livewire\Moderation\Queue as ModerationQueue;
use App\Livewire\Moderation\Rules as ModerationRules;
use App\Livewire\FactCheck\Index as FactCheckIndex;
use App\Livewire\Digest\Preferences as DigestPreferences;
use App\Livewire\Digest\History as DigestHistory;
use App\Livewire\Coins\Balance as CoinsBalance;
use App\Livewire\Memories\Index as MemoriesIndex;
use App\Livewire\ProfileFrames\Index as ProfileFramesIndex;
use App\Livewire\WatchParties\Index as WatchPartiesIndex;
use App\Livewire\WatchParties\Create as WatchPartiesCreate;
use App\Livewire\WatchParties\Show as WatchPartiesShow;
use App\Livewire\Avatars\Editor as AvatarsEditor;
use App\Livewire\Soundbites\Index as SoundbitesIndex;
use App\Livewire\Soundbites\Create as SoundbitesCreate;
use App\Livewire\Scheduling\Index as SchedulingIndex;
use App\Livewire\Scheduling\Create as SchedulingCreate;
use App\Livewire\Admin\Analytics as AdminAnalytics;
use App\Livewire\Verification\Request as VerificationRequest;
use App\Livewire\Verification\Status as VerificationStatus;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Installation Routes (no middleware - installer handles its own checks)
Route::prefix('install')->group(function () {
    Route::get('/', InstallerWelcome::class)->name('install.welcome');
    Route::get('/requirements', InstallerRequirements::class)->name('install.requirements');
    Route::get('/database', InstallerDatabase::class)->name('install.database');
    Route::get('/admin', InstallerAdmin::class)->name('install.admin');
    Route::get('/site', InstallerSite::class)->name('install.site');
    Route::get('/complete', InstallerComplete::class)->name('install.complete');
});

// Main Routes (protected by installation middleware)
Route::middleware(['installation'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {
        Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

        // Profile Edit (authenticated) - must come before {user} route
        Route::get('/profile/edit', ProfileEdit::class)->name('profile.edit');

        // Connections Routes (authenticated)
        Route::get('/friends', function () {
            return view('pages.friends');
        })->name('friends.index');
        Route::get('/friends/requests', PendingRequests::class)->name('friends.requests');
        Route::get('/followers', FollowersList::class)->name('followers.index');

        // Groups Routes (authenticated)
        Route::get('/groups/create', GroupsCreate::class)->name('groups.create');

        // Messages Routes (authenticated)
        Route::get('/messages', function () {
            return view('pages.messages');
        })->name('messages.index');
        Route::get('/messages/{conversation}', MessagesIndex::class)->name('messages.show');

        // Feed Routes (authenticated)
        Route::get('/feed', function () {
            return view('pages.feed');
        })->name('feed.index');
        Route::get('/saved', SavedPosts::class)->name('saved.index');
        Route::get('/memories', Memories::class)->name('memories.index');

        // Media Routes (authenticated)
        Route::get('/media', function () {
            return view('pages.media');
        })->name('media.index');
        Route::get('/media/albums/{album:slug}', MediaAlbumShow::class)->name('media.album');

        // Blog Routes (authenticated)
        Route::get('/blog', BlogIndex::class)->name('blog.index');
        Route::get('/blog/create', BlogCreate::class)->name('blog.create');
        Route::get('/blog/{article:slug}/edit', BlogEdit::class)->name('blog.edit');

        // Events Routes (authenticated)
        Route::get('/events', function () {
            return view('pages.events');
        })->name('events.index');
        Route::get('/events/create', EventsCreate::class)->name('events.create');
        Route::get('/events/{event}', EventsShow::class)->name('events.show');

        // Notifications Routes (authenticated)
        Route::get('/notifications', function () {
            return view('pages.notifications');
        })->name('notifications.index');

        // Onboarding Routes (authenticated)
        Route::get('/welcome', OnboardingWelcome::class)->name('onboarding.welcome');

        // Pages Routes (authenticated)
        Route::get('/pages/create', PagesCreate::class)->name('pages.create');

        // Marketplace Routes (authenticated)
        Route::get('/marketplace', function () {
            return view('pages.marketplace');
        })->name('marketplace.index');
        Route::get('/marketplace/create', MarketplaceCreate::class)->name('marketplace.create');
        Route::get('/marketplace/{listing}', MarketplaceShow::class)->name('marketplace.show');

        // Fundraisers Routes (authenticated)
        Route::get('/fundraisers', FundraisersIndex::class)->name('fundraisers.index');
        Route::get('/fundraisers/create', FundraisersCreate::class)->name('fundraisers.create');
        Route::get('/fundraisers/{fundraiser}', FundraisersShow::class)->name('fundraisers.show');

        // Watch/Video Routes (authenticated)
        Route::get('/watch', WatchIndex::class)->name('watch.index');

        // Settings Routes (authenticated)
        Route::get('/settings/close-friends', CloseFriends::class)->name('settings.close-friends');
        Route::get('/settings/restricted', RestrictedUsers::class)->name('settings.restricted');

        // Security Routes (authenticated)
        Route::get('/security/sessions', SecuritySessions::class)->name('security.sessions');

        // Collections Routes (authenticated)
        Route::get('/collections', CollectionsIndex::class)->name('collections.index');
        Route::get('/collections/create', CollectionsCreate::class)->name('collections.create');
        Route::get('/collections/{collection:slug}', CollectionsShow::class)->name('collections.show');

        // Activity & Privacy Routes (authenticated)
        Route::get('/activity', ActivityLog::class)->name('activity.index');
        Route::get('/privacy', PrivacyDashboard::class)->name('privacy.index');

        // Pokes Routes (authenticated)
        Route::get('/pokes', PokesIndex::class)->name('pokes.index');

        // Questions/AMA Routes (authenticated)
        Route::get('/questions', QuestionsIndex::class)->name('questions.index');
        Route::get('/questions/{question}/answer', QuestionsAnswer::class)->name('questions.answer');

        // Boards Routes (authenticated)
        Route::get('/boards', BoardsIndex::class)->name('boards.index');
        Route::get('/boards/create', BoardsCreate::class)->name('boards.create');
        Route::get('/boards/{board:slug}', BoardsShow::class)->name('boards.show');

        // Series Routes (authenticated)
        Route::get('/series', SeriesIndex::class)->name('series.index');
        Route::get('/series/create', SeriesCreate::class)->name('series.create');
        Route::get('/series/{series:slug}', SeriesShow::class)->name('series.show');

        // Moderation Routes (authenticated - moderators only)
        Route::get('/moderation', ModerationQueue::class)->name('moderation.queue');
        Route::get('/moderation/rules', ModerationRules::class)->name('moderation.rules');

        // Fact-Check Routes (authenticated)
        Route::get('/fact-check', FactCheckIndex::class)->name('fact-check.index');

        // Digest Routes (authenticated)
        Route::get('/digest/preferences', DigestPreferences::class)->name('digest.preferences');
        Route::get('/digest/history', DigestHistory::class)->name('digest.history');

        // Coins Routes (authenticated)
        Route::get('/coins', CoinsBalance::class)->name('coins.balance');
        Route::get('/coins/purchase', CoinsBalance::class)->name('coins.purchase');

        // Memories/On This Day Routes (authenticated)
        Route::get('/on-this-day', MemoriesIndex::class)->name('memories.on-this-day');

        // Profile Frames Routes (authenticated)
        Route::get('/profile-frames', ProfileFramesIndex::class)->name('profile-frames.index');

        // Watch Parties Routes (authenticated)
        Route::get('/watch-parties', WatchPartiesIndex::class)->name('watch-parties.index');
        Route::get('/watch-parties/create', WatchPartiesCreate::class)->name('watch-parties.create');
        Route::get('/watch-parties/{party}', WatchPartiesShow::class)->name('watch-parties.show');

        // Avatar Editor Routes (authenticated)
        Route::get('/avatar', AvatarsEditor::class)->name('avatar.editor');

        // Soundbites Routes (authenticated)
        Route::get('/soundbites', SoundbitesIndex::class)->name('soundbites.index');
        Route::get('/soundbites/create', SoundbitesCreate::class)->name('soundbites.create');

        // Post Scheduling Routes (authenticated)
        Route::get('/scheduling', SchedulingIndex::class)->name('scheduling.index');
        Route::get('/scheduling/create', SchedulingCreate::class)->name('scheduling.create');

        // Admin Analytics Routes (authenticated - admin only)
        Route::get('/admin/analytics', AdminAnalytics::class)->name('admin.analytics');

        // Verification Routes (authenticated)
        Route::get('/verification/request', VerificationRequest::class)->name('verification.request');
        Route::get('/verification/status', VerificationStatus::class)->name('verification.status');
    });

    // Hashtag Routes (public)
    Route::get('/hashtag/{tag}', HashtagShow::class)->name('hashtag.show');

    // Blog Routes (public with visibility check)
    Route::get('/blog/{article:slug}', BlogShow::class)->name('blog.show');

    // Groups Routes (public with visibility check)
    Route::get('/groups', function () {
        return view('pages.groups');
    })->name('groups.index');
    Route::get('/groups/{group:slug}', GroupsShow::class)->name('groups.show');
    Route::get('/groups/{group:slug}/members', GroupsMembers::class)->name('groups.members');

    // Profile Routes (public) - after /profile/edit to avoid conflict
    Route::get('/profile/{user}', ProfileShow::class)->name('profile.view');
    Route::get('/profile/{user}/friends', FriendsList::class)->name('profile.friends');
    Route::get('/profile/{user}/followers', FollowersList::class)->name('profile.followers');

    // Pages Routes (public)
    Route::get('/pages', PagesIndex::class)->name('pages.index');
    Route::get('/pages/{page:slug}', PagesShow::class)->name('pages.show');
});
