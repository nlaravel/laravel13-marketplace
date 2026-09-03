<?php

namespace App\Livewire\Customer;

use App\Services\Customer\CustomerProfileService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

#[Layout('components.customer-layout')]
class Profile extends Component
{
    private CustomerProfileService $profileService;

    public string $name = '';

    public string $phone = '';

    public function boot(
        CustomerProfileService $profileService
    ): void {
        $this->profileService = $profileService;
    }

    public function mount(): void
    {
        $profile = $this->profileService->getProfile(
            auth()->user()
        );

        $this->name = $profile->user->name;
        $this->phone = $profile->phone ?? '';
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],
        ];
    }

    public function updateProfile(): void
    {
        $validated = $this->validate();

        try {
            $this->profileService->updateProfile(
                auth()->user(),
                $validated
            );

            $this->dispatch(
                'show-success',
                message: 'Your profile has been updated successfully.'
            );
        } catch (Throwable) {
            $this->dispatch(
                'show-error',
                message: 'Something went wrong. Please try again.'
            );
        }
    }

    public function render(): View
    {
        return view('livewire.customer.profile');
    }
}
