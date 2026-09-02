<?php

namespace App\Livewire\Customer\Addresses;

use App\Services\Customer\AddressService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
private AddressService $addressService;

    public function boot(AddressService $addressService): void
    {
        $this->addressService = $addressService;
    }

    #[Computed]
    public function addresses(): Collection
    {
        return $this->addressService->getAddresses(auth()->user());
    }

    public function setDefault(int $addressId): void
    {
        $address = auth()->user()->addresses()->findOrFail($addressId);

        $this->addressService->setDefaultAddress(auth()->user(), $address);

        unset($this->addresses);
    }

    public function delete(int $addressId): void
    {
        $address = auth()->user()->addresses()->findOrFail($addressId);

        $this->addressService->deleteAddress(auth()->user(), $address);

        unset($this->addresses);

        session()->flash('success', 'Address deleted successfully.');
    }

    public function render(): View
    {
        return view('livewire.customer.addresses.index');
    }
}