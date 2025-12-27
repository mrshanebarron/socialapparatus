<?php

namespace App\Livewire\Profile;

use App\Models\Profile;
use App\Models\ProfileField;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public Profile $profile;

    // Basic info
    public string $username = '';
    public string $display_name = '';
    public string $bio = '';
    public string $location = '';
    public string $website = '';
    public ?string $birthday = null;
    public string $gender = '';

    // Media uploads
    public $avatar;
    public $cover_photo;

    // Privacy settings
    public string $profile_visibility = 'public';
    public bool $show_online_status = true;
    public bool $show_last_seen = true;
    public bool $allow_friend_requests = true;
    public bool $allow_messages = true;

    // Custom fields
    public array $customFields = [];

    protected function rules()
    {
        return [
            'username' => 'nullable|string|max:30|alpha_dash|unique:profiles,username,' . $this->profile->id,
            'display_name' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'birthday' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'avatar' => 'nullable|image|max:2048',
            'cover_photo' => 'nullable|image|max:4096',
            'profile_visibility' => 'required|in:public,friends,private',
            'show_online_status' => 'boolean',
            'show_last_seen' => 'boolean',
            'allow_friend_requests' => 'boolean',
            'allow_messages' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->profile = auth()->user()->getOrCreateProfile();

        $this->username = $this->profile->username ?? '';
        $this->display_name = $this->profile->display_name ?? '';
        $this->bio = $this->profile->bio ?? '';
        $this->location = $this->profile->location ?? '';
        $this->website = $this->profile->website ?? '';
        $this->birthday = $this->profile->birthday?->format('Y-m-d');
        $this->gender = $this->profile->gender ?? '';
        $this->profile_visibility = $this->profile->profile_visibility;
        $this->show_online_status = $this->profile->show_online_status;
        $this->show_last_seen = $this->profile->show_last_seen;
        $this->allow_friend_requests = $this->profile->allow_friend_requests;
        $this->allow_messages = $this->profile->allow_messages;

        // Load custom field values
        $this->loadCustomFields();
    }

    protected function loadCustomFields()
    {
        $fields = ProfileField::active()->visibleInProfile()->ordered()->get();

        foreach ($fields as $field) {
            $value = $this->profile->fieldValues()
                ->where('profile_field_id', $field->id)
                ->first();

            $this->customFields[$field->id] = [
                'field' => $field,
                'value' => $value?->value ?? $field->default_value ?? '',
                'visibility' => $value?->visibility ?? $field->visibility,
            ];
        }
    }

    public function save()
    {
        $this->validate();

        // Handle avatar upload
        if ($this->avatar) {
            // Delete old avatar
            if ($this->profile->avatar) {
                Storage::delete($this->profile->avatar);
            }
            $avatarPath = $this->avatar->store('avatars', 'public');
            $this->profile->avatar = $avatarPath;
        }

        // Handle cover photo upload
        if ($this->cover_photo) {
            // Delete old cover
            if ($this->profile->cover_photo) {
                Storage::delete($this->profile->cover_photo);
            }
            $coverPath = $this->cover_photo->store('covers', 'public');
            $this->profile->cover_photo = $coverPath;
        }

        $this->profile->update([
            'username' => $this->username ?: null,
            'display_name' => $this->display_name ?: null,
            'bio' => $this->bio ?: null,
            'location' => $this->location ?: null,
            'website' => $this->website ?: null,
            'birthday' => $this->birthday ?: null,
            'gender' => $this->gender ?: null,
            'profile_visibility' => $this->profile_visibility,
            'show_online_status' => $this->show_online_status,
            'show_last_seen' => $this->show_last_seen,
            'allow_friend_requests' => $this->allow_friend_requests,
            'allow_messages' => $this->allow_messages,
        ]);

        // Save custom fields
        foreach ($this->customFields as $fieldId => $data) {
            $this->profile->fieldValues()->updateOrCreate(
                ['profile_field_id' => $fieldId],
                [
                    'value' => $data['value'] ?? null,
                    'visibility' => $data['visibility'] ?? null,
                ]
            );
        }

        $this->avatar = null;
        $this->cover_photo = null;

        session()->flash('message', 'Profile updated successfully!');
    }

    public function deleteAvatar()
    {
        if ($this->profile->avatar) {
            Storage::delete($this->profile->avatar);
            $this->profile->update(['avatar' => null]);
        }
    }

    public function deleteCoverPhoto()
    {
        if ($this->profile->cover_photo) {
            Storage::delete($this->profile->cover_photo);
            $this->profile->update(['cover_photo' => null]);
        }
    }

    public function render()
    {
        return view('livewire.profile.edit')
            ->layout('layouts.app');
    }
}
