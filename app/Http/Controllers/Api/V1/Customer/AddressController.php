<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\AddressRequest;
use App\Http\Resources\Api\Customer\AddressResource;
use App\Models\Address;
use App\Services\Customer\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AddressController extends Controller
{
    public function __construct(private readonly AddressService $addressService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $addresses = $this->addressService->getAddresses($request->user());

        return AddressResource::collection($addresses);
    }

    public function store(AddressRequest $request): AddressResource
    {
        $address = $this->addressService->createAddress($request->user(), $request->validated());

        return new AddressResource($address);
    }

    public function show(Request $request, int $address): AddressResource|JsonResponse
    {
        $address = $request->user()
            ->addresses()
            ->find($address);

        if (! $address) {
            return response()->json([
                'message' => 'Address not found.',
            ], 404);
        }

        return new AddressResource($address);
    }

    public function update(AddressRequest $request, int $address): AddressResource|JsonResponse
    {
        $addressModel = $request->user()
            ->addresses()
            ->find($address);

        if (! $addressModel) {
            return response()->json([
                'message' => 'Address not found.',
            ], 404);
        }

        $addressModel = $this->addressService->updateAddress($request->user(), $addressModel, $request->validated());

        return new AddressResource($addressModel);
    }

    public function destroy(Request $request, int $address): JsonResponse
    {
        $addressModel = $request->user()
            ->addresses()
            ->find($address);

        if (! $addressModel) {
            return response()->json([
                'message' => 'Address not found.',
            ], 404);
        }

        $this->addressService->deleteAddress($request->user(), $addressModel);

        return response()->json([
            'message' => 'Address deleted successfully.',
        ]);
    }

    public function setDefault(Request $request, int $address): AddressResource|JsonResponse
    {
        $addressModel = $request->user()
            ->addresses()
            ->find($address);

        if (! $addressModel) {
            return response()->json([
                'message' => 'Address not found.',
            ], 404);
        }

        $addressModel = $this->addressService->setDefaultAddress($request->user(), $addressModel);

        return new AddressResource($addressModel);
    }

    private function authorizeAddress(Request $request, Address $address): void
    {
        abort_unless($address->user_id === $request->user()->id, 404);
    }
}
