<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\StoreProviderAvailabilitiesRequest;
use App\Http\Resources\ProviderAvailabilitiesResource;
use App\Http\Resources\ProviderDetailResource;
use App\Http\Resources\AvailableSlotsResource;
use App\Http\Resources\ServiceResource;
use App\Services\ProviderService;
use App\Http\Resources\ProviderListResource;
use App\Services\ProviderServicesService;
use Illuminate\Http\Request;
use App\Http\Requests\Provider\StoreProviderServiceRequest;
use Illuminate\Http\JsonResponse;

class ProviderController extends Controller
{
    private ProviderService $providerService;
    private ProviderServicesService $providerServicesService;

    public function __construct(ProviderService $providerService, ProviderServicesService $providerServicesService)
    {
        $this->providerService = $providerService;
        $this->providerServicesService = $providerServicesService;
    }

    public function providers(Request $request): JsonResponse
    {
        $perPage = 10;
        $providers = $this->providerService->listProviders($perPage);

        return ProviderListResource::collection($providers)
            ->response()
            ->setStatusCode(200);
    }

    public function provider($id): JsonResponse
    {
        $provider = $this->providerService->getProvider($id);

        return ProviderDetailResource::make($provider)
            ->response()
            ->setStatusCode(200);
    }

    public function storeAvailabilities(StoreProviderAvailabilitiesRequest $request): JsonResponse
    {
        $availabilities = $this->providerService->storeAvailabilities($request);

        return ProviderAvailabilitiesResource::collection($availabilities)
            ->response()
            ->setStatusCode(200);
    }

    public function availabilities($id): JsonResponse
    {
        $availabilities = $this->providerService->getAvailabilities($id);

        return ProviderAvailabilitiesResource::collection($availabilities)
            ->response()
            ->setStatusCode(200);
    }

    public function providerServices($id): JsonResponse
    {
        $services = $this->providerServicesService->providerServices($id);

        return ServiceResource::collection($services)
            ->response()
            ->setStatusCode(200);
    }

    public function service($id): JsonResponse
    {
        $service = $this->providerServicesService->getService($id);

        return ServiceResource::make($service)
            ->response()
            ->setStatusCode(200);
    }

    public function storeService(StoreProviderServiceRequest $request): JsonResponse
    {
        $service = $this->providerServicesService->createService($request->validated());

        return ServiceResource::make($service)
            ->response()
            ->setStatusCode(200);
    }

    public function availableSlots($id, Request $request): JsonResponse
    {
        $slots = $this->providerServicesService->getAvailableSlots(
            $id,
            $request,
        );

        return response()->json(
            collect($slots)->map(function ($slot) {
                return $slot['start'] . ' - ' . $slot['end'];
            })->values(),
            200
        );
    }
}
