<?php

namespace App\Http\Controllers;

use App\Http\Requests\AverageRentalPriceRequest;
use App\Models\Rental;
use Exception;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function averageRentalPrice(AverageRentalPriceRequest $request)
    {
        try {
            $validatedValue = $request->validated();

            $rentals = Rental::query()->selectRaw('equipment_id, AVG(total_price) as averagePrice');
            if (isset($validatedValue['minDate'])) {
                $rentals->whereDate('start_date', '>=', $validatedValue['minDate']);
            }

            if (isset($validatedValue['maxDate'])) {
                $rentals->whereDate('end_date', '<=', $validatedValue['maxDate']);
            }
            $rentals->groupBy('equipment_id');
            $finalRentals = $rentals->paginate(20);

            return response()->json($finalRentals)->setStatusCode(OK);;
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
