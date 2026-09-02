<?php

namespace App\Livewire\Customer\Addresses;

use App\Services\Customer\AddressService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    private AddressService $addressService;

    public string $label = '';

    public string $recipient_name = '';

    public string $phone = '';

    public string $country = '';

    public string $city = '';

    public string $area = '';

    public string $street = '';

    public string $building = '';

    public string $apartment = '';

    public string $address_line = '';

    public string $latitude = '';

    public string $longitude = '';

    public bool $is_default = false;

    public function boot(AddressService $addressService): void
    {
        $this->addressService = $addressService;
    }

    protected function rules(): array
    {
        return [
            'label' => [
                'nullable',
                'string',
                'max:100',
            ],

            'recipient_name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'street' => [
                'required',
                'string',
                'max:255',
            ],

            'building' => [
                'nullable',
                'string',
                'max:100',
            ],

            'apartment' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address_line' => [
                'required',
                'string',
                'max:500',
            ],

            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'is_default' => [
                'boolean',
            ],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->addressService->createAddress(
            auth()->user(),
            $validated
        );

        session()->flash(
            'success',
            'Address created successfully.'
        );

        $this->redirectRoute(
            'customer.addresses.index'
        );
    }

    public function render(): View
    {
        return view(
            'livewire.customer.addresses.create'
        );
    }
}
