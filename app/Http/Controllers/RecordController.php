<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Services\MediaService;
use App\Services\RecordService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\MediaRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\RecordResource;
use App\Http\Requests\CreateRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Actions\CreateOrUpdateRecordAction;

/**
 * @OA\Tag(
 *     name="Records",
 *     description="API Endpoints for managing medical records"
 * )
 */
final class RecordController extends Controller
{
    protected RecordService $recordService;

    protected MediaService $mediaService;

    public function __construct()
    {
        $this->recordService = new RecordService;
        $this->mediaService = new MediaService;
    }

    /**
     * @OA\Post(
     *     path="/api/records",
     *     summary="Create a new medical record",
     *     tags={"Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateRecordRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(CreateRecordRequest $request, CreateOrUpdateRecordAction $action): RecordResource
    {
        $record = $action->handle($request);

        return RecordResource::make(
            $record->load([
                'media',
                'reservation',
                'ills',
                'medicines',
                'doctors'
            ])
        );
    }

    /**
     * @OA\Get(
     *     path="/api/records/{record}",
     *     summary="Get a specific medical record",
     *     tags={"Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record details retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     )
     * )
     */
    public function show(Record $record): RecordResource
    {
        return RecordResource::make($record->load([
            'media',
            'reservation',
            'ills',
            'medicines',
            'doctors'
        ]));
    }

    /**
     * @OA\Put(
     *     path="/api/records/{record}",
     *     summary="Update an existing medical record",
     *     tags={"Records"},
     *
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(ref="#/components/schemas/UpdateRecordRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Record updated successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *
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
    public function update(UpdateRecordRequest $request, Record $record , CreateOrUpdateRecordAction $action): RecordResource
    {
        $record = $action->handle($request, $record);

        return RecordResource::make($record->load([
            'media',
            'reservation',
            'ills',
            'medicines',
            'doctors'
        ]));
    }

    /**
     * @OA\Delete(
     *     path="/api/records/{record}",
     *     summary="Soft delete a medical record",
     *     tags={"Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
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
     *
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Record restored successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *
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
     *     path="/api/records/{record}/files",
     *     summary="Add files to a medical record",
     *     tags={"Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *         @OA\Schema(type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/MediaRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Media added successfully",
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function addFile(Record $record, MediaRequest $request): MediaResource
    {
        return MediaResource::make(
            $this->handleMediaUpload($request->file('upload') , $record , $request->input('collection'))
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/records/{record}/files/{file}",
     *     summary="Delete media from a medical record",
     *     description="Removes a specific media file associated with a medical record. Only media belonging to the specified record can be deleted.",
     *     tags={"Records"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         required=true,
     *         description="Record UUID",
     *
     *         @OA\Schema(
     *             type="string",
     *             format="uuid",
     *             example="550e8400-e29b-41d4-a716-446655440000"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="media",
     *         in="path",
     *         required=true,
     *         description="Media ID to be deleted",
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Media deleted successfully",
     *
     *         @OA\JsonContent(ref="#/components/schemas/MediaResource")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Media does not belong to this record",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="not Allowed."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Record or Media not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Not found."
     *             )
     *         )
     *     )
     * )
     */
    public function deleteFile(Record $record, Media $file): MediaResource|JsonResponse
    {
        if(!$this->handleMediaDeletion($record , $file))
            return response()->json(['message' => 'not Allowed.'], ResponseAlias::HTTP_FORBIDDEN);

        return MediaResource::make($file);
    }
}
