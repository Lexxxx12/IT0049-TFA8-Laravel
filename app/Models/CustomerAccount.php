<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerAccount extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['customer_id', 'email', 'password', 'is_active', 'session_version'];

    protected $attributes = ['is_active' => true, 'session_version' => 1];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed', 'is_active' => 'boolean', 'session_version' => 'integer'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
