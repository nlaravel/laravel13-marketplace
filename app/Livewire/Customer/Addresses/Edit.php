<?php

declare(strict_types=1);

namespace App\Livewire\Customer\Addresses;

use App\Models\Address;
use App\Services\Customer\AddressService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
class Edit extends Component
{
    private AddressService $addressService;

    public Address $address;

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

    public function mount(Address $address): void
    {
        abort_unless((int) $address->user_id === (int) auth()->id(), 404);

        $this->address = $address;

        $this->label = $address->label ?? '';
        $this->recipient_name = $address->recipient_name ?? '';
        $this->phone = $address->phone ?? '';
        $this->country = $address->country ?? '';
        $this->city = $address->city ?? '';
        $this->area = $address->area ?? '';
        $this->street = $address->street ?? '';
        $this->building = $address->building ?? '';
        $this->apartment = $address->apartment ?? '';
        $this->address_line = $address->address_line ?? '';

        $this->latitude = $address->latitude !== null
            ? (string) $address->latitude
            : '';

        $this->longitude = $address->longitude !== null
            ? (string) $address->longitude
            : '';

        $this->is_default = (bool) $address->is_default;
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

        $this->addressService->updateAddress(auth()->user(), $this->address, $validated);

        session()->flash('success', 'Address updated successfully.');

        $this->redirectRoute('customer.addresses.index');
    }

    public function render(): View
    {
        return view('livewire.customer.addresses.edit');
    }
}
