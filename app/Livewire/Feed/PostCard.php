<?php

namespace App\Livewire\Feed;

use App\Models\HiddenPost;
use App\Models\Post;
use App\Models\Reaction;
use App\Models\Report;
use App\Traits\WithToast;
use Livewire\Component;

class PostCard extends Component
{
    use WithToast;
    public Post $post;
    public bool $showComments = false;
    public bool $showReactions = false;
    public bool $showReactionsModal = false;
    public bool $showShareModal = false;
    public bool $showEditModal = false;
    public bool $showEditHistory = false;
    public bool $showReportModal = false;
    public string $newComment = '';
    public string $shareBody = '';
    public string $shareVisibility = 'public';
    public string $editBody = '';
    public string $reportReason = '';
    public string $reportDetails = '';
    public ?int $replyingToCommentId = null;
    public string $replyBody = '';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function toggleLike()
    {
        if (!auth()->check()) return;

        $this->post->toggleLike(auth()->user());
        $this->post->refresh();
    }

    public function react($type)
    {
        if (!auth()->check()) return;
        if (!in_array($type, Reaction::TYPES)) return;

        $this->post->addReaction(auth()->user(), $type);
        $this->post->refresh();
        $this->showReactions = false;
    }

    public function removeReaction()
    {
        if (!auth()->check()) return;

        $this->post->removeReaction(auth()->user());
        $this->post->refresh();
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function addComment()
    {
        if (!auth()->check() || !trim($this->newComment)) return;

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'body' => trim($this->newComment),
        ]);

        $this->post->increment('comments_count');
        $this->newComment = '';
        $this->post->refresh();
    }

    public function likeComment(int $commentId)
    {
        if (!auth()->check()) return;

        $comment = \App\Models\Comment::find($commentId);
        if (!$comment) return;

        $existingLike = $comment->likes()->where('user_id', auth()->id())->first();

        if ($existingLike) {
            $existingLike->delete();
            $comment->decrement('likes_count');
        } else {
            $comment->likes()->create(['user_id' => auth()->id()]);
            $comment->increment('likes_count');
        }

        $this->post->refresh();
    }

    public function startReply(int $commentId)
    {
        $this->replyingToCommentId = $commentId;
        $this->replyBody = '';
    }

    public function cancelReply()
    {
        $this->replyingToCommentId = null;
        $this->replyBody = '';
    }

    public function submitReply()
    {
        if (!auth()->check() || !trim($this->replyBody) || !$this->replyingToCommentId) return;

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'parent_id' => $this->replyingToCommentId,
            'body' => trim($this->replyBody),
        ]);

        $this->post->increment('comments_count');
        $this->replyingToCommentId = null;
        $this->replyBody = '';
        $this->post->refresh();
    }

    public function sharePost()
    {
        if (!auth()->check()) return;

        $this->post->shareBy(auth()->user(), $this->shareBody ?: null, $this->shareVisibility);
        $this->showShareModal = false;
        $this->shareBody = '';
        $this->shareVisibility = 'public';
        $this->post->refresh();

        $this->dispatch('postShared');
        $this->success('Post shared successfully!');
    }

    public function savePost()
    {
        if (!auth()->check()) return;

        $saved = $this->post->toggleSave(auth()->user());
        $this->post->refresh();

        $this->success($saved ? 'Post saved to your collection!' : 'Post removed from saved items.');
    }

    public function openEditModal()
    {
        if ($this->post->user_id !== auth()->id()) return;

        $this->editBody = $this->post->body ?? '';
        $this->showEditModal = true;
    }

    public function saveEdit()
    {
        if ($this->post->user_id !== auth()->id()) return;
        if (!trim($this->editBody)) return;

        $this->post->edit(trim($this->editBody), request()->ip());
        $this->showEditModal = false;
        $this->post->refresh();
        $this->success('Post updated successfully!');
    }

    public function votePoll($optionId)
    {
        if (!auth()->check()) return;
        if (!$this->post->poll) return;

        $this->post->poll->vote(auth()->user(), [$optionId]);
        $this->post->refresh();
    }

    public function deletePost()
    {
        if ($this->post->user_id !== auth()->id()) return;

        auth()->user()->profile?->decrement('posts_count');
        $this->post->delete();
        $this->dispatch('postDeleted');
        $this->success('Post deleted successfully.');
    }

    public function hidePost()
    {
        if (!auth()->check()) return;

        HiddenPost::hide(auth()->user(), $this->post);
        $this->dispatch('postHidden');
        $this->info('Post hidden from your feed.');
    }

    public function reportPost()
    {
        if (!auth()->check()) return;
        if (!$this->reportReason) return;

        Report::create([
            'reporter_id' => auth()->id(),
            'reportable_type' => Post::class,
            'reportable_id' => $this->post->id,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails ?: null,
            'status' => 'pending',
        ]);

        $this->showReportModal = false;
        $this->reportReason = '';
        $this->reportDetails = '';

        $this->success('Thank you for your report. We will review it shortly.');
    }

    public function render()
    {
        $userReaction = auth()->check() ? $this->post->getUserReaction(auth()->user()) : null;
        $reactionCounts = $this->post->getReactionCounts();

        $isSaved = auth()->check() ? $this->post->isSavedBy(auth()->user()) : false;

        return view('livewire.feed.post-card', [
            'userReaction' => $userReaction,
            'reactionCounts' => $reactionCounts,
            'reactionTypes' => Reaction::TYPES,
            'reactionEmojis' => Reaction::EMOJIS,
            'isSaved' => $isSaved,
            'reportReasons' => Report::REASONS,
            'feelingEmojis' => Post::FEELING_EMOJIS,
        ]);
    }
}
