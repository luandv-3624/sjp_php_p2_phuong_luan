<?php

namespace App\Http\Controllers\Amenity;

use App\Services\Amenity\AmenityService;
use App\Models\Amenity;
use App\Models\Venue;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amenity\StoreAmenityRequest;
use App\Http\Requests\Amenity\UpdateAmenityRequest;
use App\Http\Requests\Amenity\ListByVenueRequest;

class AmenityController extends Controller
{
    protected $amenityService;

    public function __construct(AmenityService $amenityService)
    {
        $this->amenityService = $amenityService;
    }

    public function store(StoreAmenityRequest $request)
    {
        $data = $request->validated();

        return $this->amenityService->createAmenity($data);
    }

    public function update(UpdateAmenityRequest $request, Amenity $amenity)
    {
        $data = $request->validated();

        return $this->amenityService->updateAmenity($amenity, $data);
    }

    public function destroy(Amenity $amenity)
    {
        return $this->amenityService->deleteAmenity($amenity);
    }

    public function show(Amenity $amenity)
    {
        return $this->amenityService->getAmenity($amenity);
    }

    public function listByVenue(ListByVenueRequest $request, Venue $venue)
    {
        $filters = $request->validated();
        $pageSize = $filters['pageSize'] ?? null;

        return $this->amenityService->listAmenitiesByVenue($venue->id, $filters, $pageSize);
    }
}
