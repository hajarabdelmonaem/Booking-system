<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateProviderProfileRequest;
use App\Services\ProviderProfileService;
use App\Http\Resources\ProviderDetailResource;
use Illuminate\Http\JsonResponse;

class ProviderProfileController extends Controller
{
    private ProviderProfileService $providerProfileService;
    
    public function __construct(ProviderProfileService $providerProfileService)
    {
        $this->providerProfileService = $providerProfileService;
    }

        public function store(CreateProviderProfileRequest $request): JsonResponse
        {
        $provider = $this->providerProfileService->createProviderProfile($request->validated());

        return ProviderDetailResource::make($provider)
            ->response()
            ->setStatusCode(200);
    }
}
