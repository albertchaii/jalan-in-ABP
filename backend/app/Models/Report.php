<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
        'photo_path',
        'latitude',
        'longitude',
        'damage_type',
        'ai_photo_path',
        'status',
        'operator_id',
        'rejection_note',
        'evidence_photo_path',
        'verified_at',
        'completed_at',
    ];

    protected $casts = [
        'verified_at'  => 'datetime',
        'completed_at' => 'datetime',
        'latitude'     => 'float',
        'longitude'    => 'float',
    ];

    /**
     * Warna pin per status untuk Leaflet map.
     */
    public function statusColor(): string
    {
        return match($this->status) {
            'Dilaporkan'  => '#EF4444',
            'Disurvey'    => '#F59E0B',
            'Tidak Valid' => '#374151',
            'Diproses'    => '#3B82F6',
            'Selesai'     => '#22C55E',
            default       => '#9CA3AF',
        };
    }

    /**
     * Apakah laporan ini visibel secara publik di peta mobile?
     */
    public function isPubliclyVisible(): bool
    {
        return in_array($this->status, ['Dilaporkan', 'Disurvey', 'Diproses']);
    }

    /**
     * Relasi ke pelapor (User)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke operator yang menangani
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
