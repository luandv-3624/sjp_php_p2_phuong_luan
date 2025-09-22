<?php

namespace App\Services\Venue;

use App\Http\Resources\Venue\VenueCollection;
use App\Repositories\Venue\VenueRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Enums\VenueStatus;
use App\Helpers\ApiResponse;
use App\Enums\HttpStatusCode;
use App\Enums\Role;
use Illuminate\Support\Facades\Gate;
use App\Models\Venue;

class VenueService
{
    protected $venueRepo;

    public function __construct(VenueRepositoryInterface $venueRepo)
    {
        $this->venueRepo = $venueRepo;
    }

    public function createVenue(array $data)
    {
        $data['owner_id'] = Auth::id();

        $data['status'] = VenueStatus::PENDING->value;

        $venue = $this->venueRepo->create($data);

        return ApiResponse::success($venue, __('venue.created_success'), HttpStatusCode::CREATED);
    }

    public function findAll(array $filters, ?int $pageSize)
    {
        return ApiResponse::success(new VenueCollection($this->venueRepo->findAll($filters, $pageSize)));
    }

    public function updateVenue(Venue $venue, array $data)
    {
        if (Gate::denies('update', $venue)) {
            return ApiResponse::error(__('venue.permission_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        $venue =  $this->venueRepo->update($venue->id, $data);

        if ($venue) {
            return ApiResponse::success($venue, __('venue.updated_success'));
        }

        return ApiResponse::error(__('venue.updated_fail'), [], HttpStatusCode::BAD_REQUEST);
    }

    public function getVenueDetail(Venue $venue)
    {
        $venue = $this->venueRepo->findById($venue->id);

        $user = Auth::user();

        $isOwner = $venue->owner_id === $user->id;

        $isManager = $venue->managers->contains('id', $user->id);

        $isAdminOrMod = in_array($user->role->name, [Role::ADMIN, Role::MODERATOR]);

        if (!($isOwner || $isManager || $isAdminOrMod)) {
            $venue = $this->venueRepo->findByIdCustomer($venue->id);
        }

        return ApiResponse::success($venue, __('venue.get_venue_detail_success'));
    }

    public function deleteVenue(Venue $venue)
    {
        if (!$venue) {
            return ApiResponse::error(__('venue.not_found'), [], HttpStatusCode::NOT_FOUND);
        }

        if (Gate::denies('delete', $venue)) {
            return ApiResponse::error(__('venue.permission_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        $venue =  $this->venueRepo->delete($venue->id);

        if ($venue) {
            return ApiResponse::success($venue, __('venue.delete_success'));
        }

        return ApiResponse::error(__('venue.delete_fail'), [], HttpStatusCode::BAD_REQUEST);
    }

    public function addManager(Venue $venue, array $userIds)
    {
        // Check permissions: only owners can add managers
        if (Gate::denies('addManager', $venue)) {
            return ApiResponse::error(__('venue.permission_denied'), [], HttpStatusCode::FORBIDDEN);
        }

        $managers =  $this->venueRepo->addManager($venue->id, $userIds);

        return ApiResponse::success($managers, __('venue.add_manager_success'));
    }

    // Admin | Moderator block/approve venue
    public function updateStatusVenue(Venue $venue, string $status)
    {
        $data = ['status' => $status];

        $venue = $this->venueRepo->update($venue->id, $data);

        if (!$venue) {
            return ApiResponse::error(__('venue.updated_fail'), [], HttpStatusCode::BAD_REQUEST);
        }

        switch ($status) {
            case VenueStatus::BLOCKED->value:
                return ApiResponse::success($venue, __('venue.block_venue_success'));
            case VenueStatus::APPROVED->value:
                return ApiResponse::success($venue, __('venue.approve_venue_success'));
            default:
                return ApiResponse::success($venue, __('venue.updated_status_success'));
        }
    }

    public function findAllByUser(int $userId)
    {
        return ApiResponse::success(new VenueCollection($this->venueRepo->findAllByUser($userId)));
    }
}
