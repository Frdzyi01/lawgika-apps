<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'company_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Role Helper Methods ──────────────────────────────────────────────────

    /**
     * SPV / Super Admin — akses penuh ke seluruh sistem.
     * Role 'admin' adalah role lama yang sekarang berfungsi sebagai SPV.
     */
    public function isSPV(): bool
    {
        return in_array($this->role, ['admin', 'spv']);
    }

    /**
     * Admin 1 — fokus pada manajemen Order.
     */
    public function isAdmin1(): bool
    {
        return $this->role === 'admin1';
    }

    /**
     * Admin 2 — fokus pada manajemen Konten.
     */
    public function isAdmin2(): bool
    {
        return $this->role === 'admin2';
    }

    /**
     * True jika user memiliki salah satu role admin (bukan customer).
     */
    public function hasAdminAccess(): bool
    {
        return in_array($this->role, ['admin', 'spv', 'admin1', 'admin2']);
    }

    /**
     * Label tampilan untuk role pengguna.
     */
    public function roleLabel(): string
    {
        return match($this->role) {
            'admin', 'spv' => 'SPV',
            'admin1'       => 'Admin Order',
            'admin2'       => 'Admin Konten',
            'customer'     => 'Pelanggan',
            default        => ucfirst($this->role),
        };
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function meetingRoomBookings()
    {
        return $this->hasMany(MeetingRoomBooking::class);
    }

    public function podcastRoomBookings()
    {
        return $this->hasMany(PodcastRoomBooking::class);
    }
}
