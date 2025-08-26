<?php

namespace App\Services\Amenity;

use App\Models\Amenity;
use App\Repositories\Amenity\AmenityRepositoryInterface;
use App\Helpers\ApiResponse;
use App\Enums\HttpStatusCode;
use App\Repositories\Venue\VenueRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class AmenityService
{
    protected $amenityRepo;
    protected $venueRepo;

    public function __construct(AmenityRepositoryInterface $amenityRepo, VenueRepositoryInterface $venueRepo)
    {
        $this->amenityRepo = $amenityRepo;
        $this->venueRepo = $venueRepo;
    }

    public function createAmenity(array $data)
    {
        $venue = $this->venueRepo->findById($data['venue_id']);

        if (Gate::denies('create', [Amenity::class, $venue])) {
            return ApiResponse::error(__('amenity.permission_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        $amenity =  $this->amenityRepo->create($data);

        return ApiResponse::success($amenity, __('amenity.created_success'), HttpStatusCode::CREATED);
    }

    public function updateAmenity(Amenity $amenity, array $data)
    {
        // Get the new venue (if has), otherwise keep the current venue
        $targetVenue = !empty($data['venue_id'])
            ? $this->venueRepo->findById($data['venue_id'])
            : null;

        if (Gate::denies('update', [$amenity, $targetVenue])) {
            return ApiResponse::error(__('amenity.permission_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        $updatedAmenity = $this->amenityRepo->update($amenity->id, $data);

        return $updatedAmenity
            ? ApiResponse::success($updatedAmenity, __('amenity.updated_success'))
            : ApiResponse::error(__('amenity.update_fail'), [], HttpStatusCode::BAD_REQUEST);
    }

    public function deleteAmenity(Amenity $amenity)
    {
        if (Gate::denies('delete', $amenity)) {
            return ApiResponse::error(__('amenity.permission_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        $deleted = $this->amenityRepo->delete($amenity->id);

        if ($deleted) {
            return ApiResponse::success([], __('amenity.deleted_success'));
        }

        return ApiResponse::error(__('amenity.delete_fail'), [], HttpStatusCode::BAD_REQUEST);
    }

    public function getAmenity(Amenity $amenity)
    {
        $amenity = $this->amenityRepo->findById($amenity->id);

        if ($amenity) {
            return ApiResponse::success($amenity, __('amenity.get_success'));
        }

        return ApiResponse::error(__('amenity.get_fail'), [], HttpStatusCode::BAD_REQUEST);
    }

    public function listAmenitiesByVenue(int $venueId, array $filters, ?int $pageSize)
    {
        $amenities = $this->amenityRepo->getByVenue($venueId, $filters, $pageSize);

        return ApiResponse::success($amenities, __('amenity.list_success'));
    }
}
