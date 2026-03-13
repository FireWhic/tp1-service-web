<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;

class EquipmentController extends Controller
{
    #[OA\Get(
        path: '/api/equipment',
        summary: 'Récupérer la liste des équipements',
        description: 'Retourner la liste des équipements',
        tags: ['Equipment'],
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function index()
    {
        try {
            return EquipmentResource::collection(Equipment::all())->response()->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    #[OA\Get(
        path: '/api/equipment/{id}',
        summary: 'Afficher un équipement',
        tags: ['Equipment'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l`équipement',
                in: 'path',
                required: true,
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 404, description: 'Équipement non trouvé')
        ]
    )]
    public function show(string $id)
    {
        try {
            return (new EquipmentResource(Equipment::findOrFail($id)))->response()->setStatusCode(OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
    
    #[OA\Get(
        path: '/api/equipment/popularity',
        summary: 'Afficher l`indice de popularité de chaque équipement',
        description: 'Retourne les indices de popularité des équipements',
        tags: ['Equipment'],
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function showPopularity()
    {
        try {
            $popularity = Equipment::query()
                ->leftJoin('rentals', 'equipment.id', '=', 'rentals.equipment_id')
                ->leftJoin('reviews', 'rentals.id', '=', 'reviews.rental_id')
                ->select(
                    'equipment.id',
                    //je n'ai pas trouvé d'autre moyen qu'en utilisant du SQL
                    //https://laravel.com/docs/12.x/queries#raw-expressions
                    Equipment::raw('(COUNT(DISTINCT rentals.id) * 0.6 + COALESCE(AVG(reviews.rating),0) * 0.4) AS popularity')
                )
                ->groupBy('equipment.id')
                ->get();

            return response()->json($popularity)->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
