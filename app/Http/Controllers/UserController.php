<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/users',
        summary: 'Récupérer la liste des utilisateurs',
        description: 'Retourner la liste des utilisateurs',
        tags: ['Users'],
        responses: [new OA\Response(response: 200, description: 'OK')]
    )]
    public function index()
    {
        try {
            return UserResource::collection(User::all())->response()->setStatusCode(OK);
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    #[OA\Post(
        path: '/api/users',
        summary: 'Ajouter un utilisateur',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'first_name', type: 'string', example: 'Samuel'),
                        new OA\Property(property: 'last_name', type: 'string', example: 'Lechamplain'),
                        new OA\Property(property: 'email', type: 'string', example: 'oui@gmail.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '418-321-3210')
                    ]
                )
            ]
        ),
        responses: [
            new OA\Response(response: 201, description: 'Utilisateur ajouté'),
            new OA\Response(response: 422, description: 'Données invalides')
        ]
    )]
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create($request->validated());
            return (new UserResource($user))->response()->setStatusCode(CREATED);
        } catch (QueryException $ex) {
            abort(INVALID_DATA, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }

    #[OA\Patch(
        path: '/api/users/{id}',
        summary: 'Modifier un utilisateur',
        tags: ['Users'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'first_name', type: 'string', example: 'Samuel'),
                        new OA\Property(property: 'last_name', type: 'string', example: 'Lechamplain'),
                        new OA\Property(property: 'email', type: 'string', example: 'oui@gmail.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '418-321-3210')
                    ]
                )
            ]
        ),
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'ID de l`utilisateur',
                in: 'path',
                required: true,
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 422, description: 'Données invalides')
        ]
    )]
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update($request->all());

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user
            ], OK);
        } catch (QueryException $ex) {
            abort(INVALID_DATA, 'Cannot be created in database');
        } catch (Exception $ex) {
            abort(SERVER_ERROR, 'Server error');
        }
    }
}
