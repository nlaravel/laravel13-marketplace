<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 use Laravel\Fortify\TwoFactorAuthenticatable;
 use Spatie\Permission\Traits\HasRoles;
 use Illuminate\Database\Eloquent\Relations\HasMany;
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */

    use HasApiTokens, HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function deliveryProfile()
    {
        return $this->hasOne(DeliveryProfile::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function sellerProfile()
    {
        return $this->hasOne(SellerProfile::class);
    }
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
