<?php

namespace App\Services;

use App\Http\Requests\Provider\StoreProviderAvailabilitiesRequest;
use App\Models\Provider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProviderService
{
    public function listProviders(int $perPage = 10)
    {
        return Provider::with('user')
            ->withCount('services')
            ->paginate($perPage);
    }

    public function getProvider(int|string $id): Provider
    {
        return Provider::whereKey($id)->with(['availabilities', 'user'])->withCount('services')->firstOrFail();
    }


    public function storeAvailabilities(StoreProviderAvailabilitiesRequest $request): Collection
    {
        $provider = auth()->user()->provider;
        $availabilities = $request->validated('availabilities');
        $provider->availabilities()->delete();
        $provider->availabilities()->createMany($availabilities);

        return $provider->availabilities;
    }


    public function getAvailabilities(int|string $id): Collection
    {
        $provider = Provider::whereKey($id)
            ->firstOrFail();

        return $provider->availabilities()
            ->orderBy('day')
            ->get();
    }
}
