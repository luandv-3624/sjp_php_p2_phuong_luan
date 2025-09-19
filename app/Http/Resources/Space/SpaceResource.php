<?php

namespace App\Http\Resources\Space;

use App\Http\Resources\Venue\VenueResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SpaceResource extends JsonResource
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
            'venue_id' => $this->venue_id,
            'venue' => new VenueResource($this->whenLoaded('venue')),
            'name' => $this->name,
            'space_type' => new SpaceTypeResource($this->whenLoaded('type')),
            'capacity' => $this->capacity,
            'price_type' => new PriceTypeResource($this->whenLoaded('priceType')),
            'price' => $this->price,
            'amenities'  => AmenityResource::collection($this->whenLoaded('amenities')),
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
