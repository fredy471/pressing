<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['agent_id', 'user_id', 'tracking_code', 'status', 'pickup_phone', 'pickup_adress', 'delivery_mode', 'total_amount', 'deposit_deadline', 'deposited_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->tracking_code = $order->generateUniqueTrackingCode();
        });
    }

    private function generateUniqueTrackingCode(): string
    {
        do {
            $code = 'PRS-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (self::where('tracking_code', $code)->exists());

        return $code;
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = [
            'en_attente' => ['recue', 'annulee'],
            'recue' => ['en_lavage', 'annulee'],
            'en_lavage' => ['terminee'],
            'terminee' => ['livree'],
            'livree' => [],
            'annulee' => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }
}
