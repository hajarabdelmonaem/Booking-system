<?php

namespace App\Services;

use App\Models\Provider;
use App\Models\User;

class ProviderProfileService
{
    public function createProviderProfile(array $data): Provider
    {
        $user = auth()->user();
        $provider = $user->provider()->updateOrCreate($data);
        $user->update(['is_provider' => true]);
        return $provider;
    }
}
