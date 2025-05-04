<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class Store extends Authenticatable implements FilamentUser
{
    protected $fillable = ['name', 'email', 'password', 'currency_id'];

    protected $hidden = ['password'];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
