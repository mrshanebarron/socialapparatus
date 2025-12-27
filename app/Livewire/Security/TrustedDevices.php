<?php

namespace App\Livewire\Security;

use Livewire\Component;

class TrustedDevices extends Component
{
    public function removeDevice($deviceId)
    {
        auth()->user()->trustedDevices()->where('id', $deviceId)->delete();
    }

    public function removeAllDevices()
    {
        auth()->user()->trustedDevices()->delete();
    }

    public function render()
    {
        $devices = auth()->user()->trustedDevices()
            ->orderByDesc('last_used_at')
            ->get();

        return view('livewire.security.trusted-devices', [
            'devices' => $devices,
        ]);
    }
}
