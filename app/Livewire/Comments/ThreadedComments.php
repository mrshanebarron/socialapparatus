<?php

namespace App\Livewire\Comments;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ThreadedComments extends Component
{
    public $commentableType;
    public $commentableId;
    public string $newComment = '';
    public ?int $replyingTo = null;
    public string $replyContent = '';
    public int $maxDepth = 3;
    public string $sortBy = 'newest';

    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount($commentableType, $commentableId)
    {
        $this->commentableType = $commentableType;
        $this->commentableId = $commentableId;
    }

    public function addComment()
    {
        if (!auth()->check() || empty(trim($this->newComment))) return;

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => $this->commentableType,
            'commentable_id' => $this->commentableId,
            'content' => $this->newComment,
            'parent_id' => null,
            'depth' => 0,
            'path' => '',
        ]);

        // Update path with own ID
        $comment->update(['path' => (string) $comment->id]);

        $this->newComment = '';
    }

    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyContent = '';
    }

    public function submitReply()
    {
        if (!auth()->check() || empty(trim($this->replyContent)) || !$this->replyingTo) return;

        $parent = Comment::findOrFail($this->replyingTo);

        // Check depth limit
        if ($parent->depth >= $this->maxDepth) {
            session()->flash('error', 'Maximum reply depth reached.');
            return;
        }

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => $this->commentableType,
            'commentable_id' => $this->commentableId,
            'content' => $this->replyContent,
            'parent_id' => $parent->id,
            'depth' => $parent->depth + 1,
            'path' => $parent->path,
        ]);

        // Update path
        $comment->update(['path' => $parent->path . '/' . $comment->id]);

        // Update parent's reply count
        $parent->increment('replies_count');

        $this->cancelReply();
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);

        // Only owner can delete
        if ($comment->user_id !== auth()->id()) return;

        // If has replies, soft delete (mark as deleted but keep)
        if ($comment->replies_count > 0) {
            $comment->update(['content' => '[deleted]', 'is_deleted' => true]);
        } else {
            // Decrement parent's reply count
            if ($comment->parent_id) {
                Comment::where('id', $comment->parent_id)->decrement('replies_count');
            }
            $comment->delete();
        }
    }

    public function collapseThread($commentId)
    {
        $this->dispatch('collapseThread', commentId: $commentId);
    }

    public function render()
    {
        $query = Comment::where('commentable_type', $this->commentableType)
            ->where('commentable_id', $this->commentableId)
            ->with(['user.profile', 'replies.user.profile'])
            ->whereNull('parent_id');

        if ($this->sortBy === 'newest') {
            $query->orderByDesc('created_at');
        } elseif ($this->sortBy === 'oldest') {
            $query->orderBy('created_at');
        } elseif ($this->sortBy === 'popular') {
            $query->orderByDesc('likes_count');
        }

        $comments = $query->get();

        return view('livewire.comments.threaded-comments', [
            'comments' => $comments,
        ]);
    }
}
