<?php

namespace App\Http\Controllers;

use App\Http\Resources\IllResource;
use App\Models\Ill;
use Illuminate\Http\Request;
use App\Http\Requests\IllRequest;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Ills",
 *     description="API Endpoints for managing Ills"
 * )
 */
class IllController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/ills",
     *     summary="Get list of ills",
     *     tags={"Ills"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/IllResource")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return IllResource::collection(Ill::query()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/ills",
     *     summary="Create a new ill",
     *     tags={"Ills"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/IllRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ill created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/IllResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(IllRequest $request)
    {
        $ill = Ill::create($request->validated());

        if ($request->has('specifications')) {
            $ill->specifications()->sync($request->input('specifications'));
        }

        return IllResource::make($ill->load('specifications'));
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
}
