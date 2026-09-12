<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Seller\StoreRequest;
use App\Http\Resources\Api\Seller\StoreResource;
use App\Services\Seller\SellerStoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SellerStoreController extends Controller
{
    public function __construct(private readonly SellerStoreService $storeService) {}

    public function index(): AnonymousResourceCollection
    {
        $stores = $this->storeService->getStores(auth()->id());

        return StoreResource::collection($stores);
    }

    public function show(int $store): StoreResource
    {
        $storeModel = $this->storeService->getStore(auth()->id(), $store);

        return new StoreResource($storeModel);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $store = $this->storeService->createStore(auth()->id(), $request->validated());

        return (new StoreResource($store))
            ->response()
            ->setStatusCode(201);
    }

    public function update(StoreRequest $request, int $store): StoreResource
    {
        $updatedStore = $this->storeService->updateStore(auth()->id(), $store, $request->validated());

        return new StoreResource($updatedStore);
    }
}
