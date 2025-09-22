<?php

namespace App\Http\Controllers\Venue;

use App\Http\Controllers\Controller;
use App\Services\Venue\VenueService;
use App\Http\Requests\Venue\CreateVenueRequest;
use App\Http\Requests\Venue\UpdateVenueRequest;
use App\Http\Requests\Venue\UpdateVenueStatusRequest;
use App\Http\Requests\Venue\AddManagerRequest;
use App\Models\Venue;
use App\Http\Requests\Venue\IndexRequest;

class VenueController extends Controller
{
    protected $venueService;

    public function __construct(VenueService $venueService)
    {
        $this->venueService = $venueService;
    }

    public function store(CreateVenueRequest $request)
    {
        $data = $request->validated();

        return $this->venueService->createVenue($data);
    }

    public function index(IndexRequest $request)
    {
        $query = $request->validated();

        return $this->venueService->findAll($query, $query['pageSize'] ?? null);
    }

    public function update(UpdateVenueRequest $request, Venue $venue)
    {
        $data = $request->validated();

        return $this->venueService->updateVenue($venue, $data);
    }

    public function destroy(Venue $venue)
    {
        return $this->venueService->deleteVenue($venue);
    }

    public function show(Venue $venue)
    {
        return $this->venueService->getVenueDetail($venue);
    }

    public function addManager(AddManagerRequest $request, Venue $venue)
    {
        $data = $request->validated();

        return $this->venueService->addManager($venue, $data['user_ids']);
    }

    public function updateStatusVenue(UpdateVenueStatusRequest $request, Venue $venue)
    {
        $data = $request->validated();

        return $this->venueService->updateStatusVenue($venue, $data['status']);
    }
}
