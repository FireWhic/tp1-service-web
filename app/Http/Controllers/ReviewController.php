<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Delete(
        path: '/api/reviews/{id}',
        summary: 'Supprimer une critique',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de la critique',
                in: 'path',
                required: true,
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 404, description: 'Critique non trouvé')
        ]
    )]
    public function destroy(string $id)
    {
        try {
            $user = Review::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'Review deleted successfully'
            ], OK);
        } catch (ModelNotFoundException $ex) {
            abort(NOT_FOUND, 'Invalid id');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
