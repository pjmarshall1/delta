<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->when($this->id === $request->user()?->id, $this->name),
            'email' => $this->when($this->id === $request->user()?->id, $this->email),
            'profile_photo_url' => $this->when($this->id === $request->user()?->id, $this->profile_photo_url),
        ];
    }
}
