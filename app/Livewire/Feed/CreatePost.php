<?php

namespace App\Livewire\Feed;

use App\Models\Hashtag;
use App\Models\Mention;
use App\Models\Poll;
use App\Models\Post;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;
    use WithToast;

    public string $body = '';
    public string $visibility = 'public';
    public $media = [];
    public ?int $groupId = null;

    // Poll fields
    public bool $showPollForm = false;
    public string $pollQuestion = '';
    public array $pollOptions = ['', ''];
    public bool $pollAllowMultiple = false;
    public ?string $pollEndsAt = null;

    // Location fields
    public bool $showLocationForm = false;
    public string $locationName = '';
    public ?float $locationLat = null;
    public ?float $locationLng = null;

    // Scheduling fields
    public bool $showScheduleForm = false;
    public ?string $scheduledAt = null;
    public string $postStatus = 'published'; // published, draft, scheduled

    // Link URL
    public string $linkUrl = '';

    // Feeling/Activity
    public bool $showFeelingForm = false;
    public string $feeling = '';
    public string $activity = '';
    public string $activityDetail = '';

    // Background Color
    public bool $showBackgroundPicker = false;
    public string $backgroundColor = '';
    public string $backgroundGradient = '';

    // GIF
    public bool $showGifPicker = false;
    public string $gifUrl = '';
    public string $gifSearch = '';

    // Mention search
    public string $mentionSearch = '';
    public array $mentionResults = [];

    protected $rules = [
        'body' => 'required_without_all:media,pollQuestion|max:5000',
        'visibility' => 'required|in:public,friends,private',
        'media.*' => 'nullable|image|max:5120',
        'pollQuestion' => 'nullable|max:500',
        'pollOptions.*' => 'nullable|max:200',
        'locationName' => 'nullable|max:255',
        'scheduledAt' => 'nullable|date|after:now',
        'linkUrl' => 'nullable|url|max:2048',
    ];

    public function addPollOption()
    {
        if (count($this->pollOptions) < 10) {
            $this->pollOptions[] = '';
        }
    }

    public function removePollOption($index)
    {
        if (count($this->pollOptions) > 2) {
            unset($this->pollOptions[$index]);
            $this->pollOptions = array_values($this->pollOptions);
        }
    }

    public function togglePollForm()
    {
        $this->showPollForm = !$this->showPollForm;
        if (!$this->showPollForm) {
            $this->resetPoll();
        }
    }

    public function toggleLocationForm()
    {
        $this->showLocationForm = !$this->showLocationForm;
        if (!$this->showLocationForm) {
            $this->resetLocation();
        }
    }

    public function toggleScheduleForm()
    {
        $this->showScheduleForm = !$this->showScheduleForm;
        if (!$this->showScheduleForm) {
            $this->resetSchedule();
        }
    }

    public function setLocation($name, $lat = null, $lng = null)
    {
        $this->locationName = $name;
        $this->locationLat = $lat;
        $this->locationLng = $lng;
    }

    protected function resetPoll()
    {
        $this->pollQuestion = '';
        $this->pollOptions = ['', ''];
        $this->pollAllowMultiple = false;
        $this->pollEndsAt = null;
    }

    protected function resetLocation()
    {
        $this->locationName = '';
        $this->locationLat = null;
        $this->locationLng = null;
    }

    protected function resetSchedule()
    {
        $this->scheduledAt = null;
        $this->postStatus = 'published';
    }

    public function toggleFeelingForm()
    {
        $this->showFeelingForm = !$this->showFeelingForm;
        if (!$this->showFeelingForm) {
            $this->feeling = '';
            $this->activity = '';
            $this->activityDetail = '';
        }
    }

    public function setFeeling(string $feeling)
    {
        $this->feeling = $feeling;
        $this->showFeelingForm = false;
    }

    public function setActivity(string $activity)
    {
        $this->activity = $activity;
    }

    public function toggleBackgroundPicker()
    {
        $this->showBackgroundPicker = !$this->showBackgroundPicker;
    }

    public function setBackground(string $gradient)
    {
        $this->backgroundGradient = $gradient;
        $this->backgroundColor = '';
        $this->showBackgroundPicker = false;
    }

    public function clearBackground()
    {
        $this->backgroundGradient = '';
        $this->backgroundColor = '';
    }

    public function toggleGifPicker()
    {
        $this->showGifPicker = !$this->showGifPicker;
    }

    public function selectGif(string $url)
    {
        $this->gifUrl = $url;
        $this->showGifPicker = false;
    }

    public function clearGif()
    {
        $this->gifUrl = '';
    }

    public function searchMentions(string $query)
    {
        if (strlen($query) < 1) {
            $this->mentionResults = [];
            return;
        }

        $this->mentionResults = \App\Models\User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(5)
            ->get(['id', 'name', 'username', 'profile_photo_path'])
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'avatar' => $user->profile_photo_url,
            ])
            ->toArray();
    }

    public function createPost()
    {
        $this->validate();

        $mediaFiles = [];
        if ($this->media) {
            foreach ($this->media as $file) {
                $mediaFiles[] = $file->store('posts', 'public');
            }
        }

        $type = 'text';
        if (!empty($mediaFiles)) {
            $type = 'photo';
        } elseif ($this->showPollForm && $this->pollQuestion) {
            $type = 'poll';
        } elseif ($this->linkUrl) {
            $type = 'link';
        }

        // Determine status
        $status = 'published';
        $scheduledAt = null;
        if ($this->postStatus === 'draft') {
            $status = 'draft';
        } elseif ($this->postStatus === 'scheduled' && $this->scheduledAt) {
            $status = 'scheduled';
            $scheduledAt = $this->scheduledAt;
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'group_id' => $this->groupId,
            'body' => $this->body ?: null,
            'type' => $type,
            'media' => $mediaFiles ?: null,
            'visibility' => $this->visibility,
            'location_name' => $this->locationName ?: null,
            'location_lat' => $this->locationLat,
            'location_lng' => $this->locationLng,
            'scheduled_at' => $scheduledAt,
            'status' => $status,
            'link_url' => $this->linkUrl ?: null,
            'feeling' => $this->feeling ?: null,
            'activity' => $this->activity ?: null,
            'activity_detail' => $this->activityDetail ?: null,
            'background_color' => $this->backgroundColor ?: null,
            'background_gradient' => $this->backgroundGradient ?: null,
            'gif_url' => $this->gifUrl ?: null,
        ]);

        // Create poll if provided
        if ($this->showPollForm && $this->pollQuestion) {
            $validOptions = array_filter($this->pollOptions, fn($opt) => trim($opt) !== '');
            if (count($validOptions) >= 2) {
                $poll = Poll::create([
                    'post_id' => $post->id,
                    'question' => $this->pollQuestion,
                    'allow_multiple' => $this->pollAllowMultiple,
                    'ends_at' => $this->pollEndsAt ?: null,
                ]);

                foreach ($validOptions as $optionText) {
                    $poll->options()->create([
                        'option_text' => trim($optionText),
                    ]);
                }
            }
        }

        // Fetch link preview if URL provided
        if ($this->linkUrl) {
            $post->fetchLinkPreview();
        }

        // Process @mentions
        if ($this->body) {
            Mention::processMentions($post, $this->body);
            Hashtag::processHashtags($post, $this->body);
        }

        // Update profile post count (only for published posts)
        if ($status === 'published') {
            auth()->user()->profile?->increment('posts_count');
        }

        $this->reset(['body', 'media', 'linkUrl', 'feeling', 'activity', 'activityDetail', 'backgroundColor', 'backgroundGradient', 'gifUrl']);
        $this->resetPoll();
        $this->resetLocation();
        $this->resetSchedule();
        $this->showPollForm = false;
        $this->showLocationForm = false;
        $this->showScheduleForm = false;
        $this->showFeelingForm = false;
        $this->showBackgroundPicker = false;
        $this->showGifPicker = false;

        $this->dispatch('postCreated');

        // Show appropriate toast message
        match ($status) {
            'draft' => $this->info('Post saved as draft.'),
            'scheduled' => $this->success('Post scheduled for ' . $scheduledAt),
            default => $this->success('Post published successfully!'),
        };
    }

    public function saveDraft()
    {
        $this->postStatus = 'draft';
        $this->createPost();
    }

    public function schedulePost()
    {
        if (!$this->scheduledAt) {
            $this->error('Please select a date and time to schedule this post.');
            return;
        }
        $this->postStatus = 'scheduled';
        $this->createPost();
    }

    public function removeMedia($index)
    {
        unset($this->media[$index]);
        $this->media = array_values($this->media);
    }

    public function render()
    {
        return view('livewire.feed.create-post', [
            'feelings' => Post::FEELINGS,
            'feelingEmojis' => Post::FEELING_EMOJIS,
            'activities' => Post::ACTIVITIES,
            'backgroundColors' => Post::BACKGROUND_COLORS,
        ]);
    }
}
