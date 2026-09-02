<?php

namespace App\Livewire\Customer\Addresses;

use App\Models\Address;
use App\Services\Customer\AddressService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.customer-layout')]
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

    /*
    |--------------------------------------------------------------------------
    | Delete Address
    |--------------------------------------------------------------------------
    */

    public function delete(int $addressId): void
    {
        $user = auth()->user();

        $address = $this->addressService->findAddress(
            $user,
            $addressId
        );

        $this->addressService->deleteAddress(
            $user,
            $address
        );

        unset($this->addresses);

        $this->dispatch(
            'show-success',
            message: 'Address deleted successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Set Default Address
    |--------------------------------------------------------------------------
    */

    public function setDefault(int $addressId): void
    {
        $user = auth()->user();

        $address = $this->addressService->findAddress(
            $user,
            $addressId
        );

        $this->addressService->setDefaultAddress(
            $user,
            $address
        );

        unset($this->addresses);

        $this->dispatch(
            'show-success',
            message: 'Default address updated successfully.'
        );
    }

    public function render(): View
    {
        return view(
            'livewire.customer.addresses.index'
        );
    }
}
