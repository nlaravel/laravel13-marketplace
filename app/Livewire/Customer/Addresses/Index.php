<?php

namespace App\Livewire\Customer\Addresses;

use App\Models\Address;
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
        return $this->addressService->getAddresses(
            auth()->user()
        );
    }

    public function delete(int $addressId): void
    {
        $address = $this->findAddress($addressId);

        $this->addressService->deleteAddress(
            auth()->user(),
            $address
        );

        unset($this->addresses);

        session()->flash(
            'success',
            'Address deleted successfully.'
        );
    }

    public function setDefault(int $addressId): void
    {
        $address = $this->findAddress($addressId);

        $this->addressService->setDefaultAddress(
            auth()->user(),
            $address
        );

        unset($this->addresses);

        session()->flash(
            'success',
            'Default address updated successfully.'
        );
    }

    private function findAddress(int $addressId): Address
    {
        return auth()
            ->user()
            ->addresses()
            ->findOrFail($addressId);
    }

    public function render(): View
    {
        return view(
            'livewire.customer.addresses.index'
        );
    }
}
