<?php

namespace App\Http\Controllers;

use App\Http\Requests\AverageRentalPriceRequest;
use App\Models\Rental;
use Exception;
use OpenApi\Attributes as OA;

class RentalController extends Controller
{
    //voila où j'ai trouvé les paramètres query: https://swagger.io/docs/specification/v3_0/serialization/
    #[OA\Get(
        path: '/api/rentals',
        summary: 'Afficher le prix moyen des locations de chaque équipement',
        tags: ['Rentals'],
        parameters: [
            new OA\Parameter(
                name: 'minDate',
                in: 'query',
                description: 'Date minimale requise pour l`affichage',
                required: false
            ),
            new OA\Parameter(
                name: 'maxDate',
                in: 'query',
                description: 'Date maximale requise pour l`affichage',
                required: false
            )
        ],
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
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

            return response()->json($finalRentals)->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
