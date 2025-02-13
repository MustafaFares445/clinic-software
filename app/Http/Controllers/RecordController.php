<?php

namespace App\Http\Controllers;

use App\Enums\RecordIllsTypes;
use App\Enums\RecordMedicinesTypes;
use App\Http\Requests\MediaRequest;
use App\Http\Requests\RecordRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Models\Reservation;
use App\Services\MediaService;
use App\Services\RecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @OA\Tag(
 *     name="Records",
 *     description="API Endpoints for managing medical records"
 * )
 */
class RecordController extends Controller
{
    protected RecordService $recordService;
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->recordService = new RecordService();
        $this->mediaService = new MediaService();
    }

    /**
     * @OA\Post(
     *     path="/api/records",
     *     summary="Create a new medical record",
     *     tags={"Records"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patientId","type"},
     *             @OA\Property(property="patientId", type="string", format="uuid", description="Patient's UUID"),
     *             @OA\Property(property="clinicId", type="string", format="uuid", description="Clinic's UUID"),
     *             @OA\Property(property="reservationId", type="string", format="uuid", description="Reservation's UUID"),
     *             @OA\Property(property="description", type="string", description="Record description"),
     *             @OA\Property(property="type", type="string", enum={"diagnosed","transient"}, description="Record type"),
     *             @OA\Property(property="price", type="integer", description="Record price"),
     *             @OA\Property(property="doctorsIds", type="array", @OA\Items(type="string", format="uuid")),
     *             @OA\Property(property="medicines", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="note", type="string")
     *             )),
     *             @OA\Property(property="ills", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="note", type="string")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(RecordRequest $request): RecordResource
    {
        $record = Record::query()->create($request->validated());

        $this->recordService->recordRelations($record, $request);

        $this->mediaService->handleMultipleMediaUpload($record, $request);

        return RecordResource::make($record->load(['media', 'reservation', 'ills', 'medicines', 'doctors']));
    }

    /**
     * @OA\Get(
     *     path="/api/records/{record}",
     *     summary="Get a specific medical record",
     *     tags={"Records"},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     )
     * )
     */
    public function show(Record $record): RecordResource
    {
        return RecordResource::make($record->load(['media', 'reservation', 'ills', 'transientIlls', 'transientMedicines', 'medicines', 'doctors']));
    }

    /**
     * @OA\Put(
     *     path="/api/records/{record}",
     *     summary="Update an existing medical record",
     *     tags={"Records"},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RecordRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function update(RecordRequest $request, Record $record): RecordResource
    {
        $record->update($request->validated());

        $this->recordService->recordRelations($record, $request);

        $this->mediaService->handleMultipleMediaUpload($record, $request);

        return RecordResource::make($record->load(['media', 'reservation', 'ills', 'transientIlls', 'transientMedicines', 'medicines', 'doctors']));
    }

    /**
     * @OA\Delete(
     *     path="/api/records/{record}",
     *     summary="Soft delete a medical record",
     *     tags={"Records"},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     )
     * )
     */
    public function destroy(Record $record): RecordResource
    {
        $record->delete();
        return RecordResource::make($record);
    }

    /**
     * @OA\Post(
     *     path="/api/records/{record}/restore",
     *     summary="Restore a soft-deleted medical record",
     *     tags={"Records"},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record restored successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     )
     * )
     */
    public function restore(Record $record): RecordResource
    {
        $record->restore();
        return RecordResource::make($record->load(['media', 'reservation', 'ills', 'medicines', 'doctors']));
    }

    /**
     * @OA\Post(
     *     path="/api/records/{record}/media",
     *     summary="Add media to a medical record",
     *     tags={"Records"},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="image", type="file"),
     *                 @OA\Property(property="collection", type="string", enum={"files","images","audios","videos","x-ray","tests"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Media added successfully",
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     )
     * )
     */
    public function addMedia(Record $record, MediaRequest $request): MediaResource
    {
        return MediaResource::make(
            $this->mediaService->handleMediaUpload($record, $request->file('image'), $request->input('collection'))
        );
    }

   /**
     * @OA\Delete(
     *     path="/api/records/{record}/media/{media}",
     *     summary="Delete media from a medical record",
     *     description="Removes a specific media file associated with a medical record. Only media belonging to the specified record can be deleted.",
     *     tags={"Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="media",
     *         in="path",
     *         required=true,
     *         description="Media ID to be deleted",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Media deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Media does not belong to this record",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="not Allowed."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record or Media not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Not found."
     *             )
     *         )
     *     )
     * )
     */
    public function deleteMedia(Record $record, Media $media): MediaResource|JsonResponse
    {
        if ($media->getMorphClass() != $record) {
            return response()->json(['message' => 'not Allowed.'], ResponseAlias::HTTP_FORBIDDEN);
        }

        $media->delete();
        return MediaResource::make($media);
    }
}
