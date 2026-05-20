<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'photo_url' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'damage_type' => $this->damage_type,
            'ai_photo_url' => $this->ai_photo_path ? asset('storage/' . $this->ai_photo_path) : null,
            'status' => $this->status,
            'status_color' => $this->statusColor(),
            'is_mine' => $this->user_id === auth('sanctum')->id(),
            'operator_name' => $this->operator ? $this->operator->name : null,
            'rejection_note' => $this->rejection_note,
            'evidence_photo_url' => $this->evidence_photo_path ? asset('storage/' . $this->evidence_photo_path) : null,
            'verified_at' => $this->verified_at ? $this->verified_at->toDateTimeString() : null,
            'completed_at' => $this->completed_at ? $this->completed_at->toDateTimeString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
