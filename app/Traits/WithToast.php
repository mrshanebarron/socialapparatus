<?php

namespace App\Traits;

trait WithToast
{
    /**
     * Show a success toast notification
     */
    public function success(string $message, ?string $title = null, int $duration = 5000): void
    {
        $this->toast('success', $message, $title, $duration);
    }

    /**
     * Show an error toast notification
     */
    public function error(string $message, ?string $title = null, int $duration = 5000): void
    {
        $this->toast('error', $message, $title, $duration);
    }

    /**
     * Show a warning toast notification
     */
    public function warning(string $message, ?string $title = null, int $duration = 5000): void
    {
        $this->toast('warning', $message, $title, $duration);
    }

    /**
     * Show an info toast notification
     */
    public function info(string $message, ?string $title = null, int $duration = 5000): void
    {
        $this->toast('info', $message, $title, $duration);
    }

    /**
     * Dispatch a toast notification to the browser
     */
    public function toast(string $type, string $message, ?string $title = null, int $duration = 5000): void
    {
        $this->dispatch('toast',
            type: $type,
            message: $message,
            title: $title,
            duration: $duration,
        );
    }
}
