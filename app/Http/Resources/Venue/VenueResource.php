<?php

namespace App\Http\Resources\Venue;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'name' => $this->name,
            'address' => $this->address,
            'ward_id' => $this->ward,
            'ward' => new WardResource($this->whenLoaded('ward')),
            'managers' => UserResource::collection($this->whenLoaded('managers')),
            'lat' => $this->lat,
            'lng' => $this->lng,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
