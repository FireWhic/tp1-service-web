<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return EquipmentResource::collection(Equipment::all())->response()->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    /**
     * Display the specified resource.
     */
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
