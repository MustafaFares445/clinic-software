<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use App\Http\Resources\RecordResource;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @OA\Tag(
 *     name="Records",
 *     description="API Endpoints for managing medical records"
 * )
 */
final class RecordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/records",
     *     summary="Create a new record",
     *     tags={"Records"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreRecordRequest")
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
    public function store(StoreRecordRequest $request)
    {
        $record = Record::create($request->validated());

        $record->load(['patient', 'tooth', 'treatment', 'fillingMaterial', 'medicalSession', 'doctors.media' , 'media ']);

        return RecordResource::make($record);
    }

    /**
     * @OA\Get(
     *     path="/api/records/{record}",
     *     summary="Get a specific record",
     *     tags={"Records"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         description="Record ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record details",
     *         @OA\JsonContent(ref="#/components/schemas/RecordResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     )
     * )
     */
    public function show(Record $record)
    {
        $record->load(['patient', 'tooth', 'treatment', 'fillingMaterial', 'medicalSession', 'doctors.media' , 'media']);

        return RecordResource::make($record);
    }

    /**
     * @OA\Put(
     *     path="/api/records/{record}",
     *     summary="Update a record",
     *     tags={"Records"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         description="Record ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateRecordRequest")
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
    public function update(UpdateRecordRequest $request, Record $record)
    {
        $record->update($request->validated());

        $record->load(['patient', 'tooth', 'treatment', 'fillingMaterial', 'medicalSession', 'doctors.media' , 'media']);

        return RecordResource::make($record);
    }

    /**
     * @OA\Delete(
     *     path="/api/records/{record}",
     *     summary="Delete a record",
     *     tags={"Records"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         description="Record ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Record deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Record not found"
     *     )
     * )
     */
    public function destroy(Record $record)
    {
        $record->delete();

        return response()->noContent();
    }


    /**
     * @OA\Post(
     *     path="/api/records/{record}/file",
     *     summary="Upload new file",
     *     tags={"Records"},
     *     security={{ "bearerAuth": {} }},
     *
     *    @OA\Parameter(
     *          name="record",
     *          in="path",
     *          description="Record ID",
     *          required=true,
     *
     *          @OA\Schema(type="string", format="uuid")
     *      ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                      format="binary",
     *                     description="Files to upload"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name of the files"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Files uploaded successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function addFile(Record $record, Request $request): MediaResource
    {
        $media = $this->handleMediaUpload(request: $request, model: $record, name: $request->input('name'));

        return MediaResource::make($media);
    }

    /**
     * @OA\Delete(
     *     path="/api/records/{record}/file/{media}",
     *     summary="Delete a record file",
     *     description="Delete a specific file associated with a record",
     *     operationId="deleteRecordFile",
     *     tags={"Records"},
     *     security={{ "bearerAuth": {} }},
     *
     *     @OA\Parameter(
     *         name="record",
     *         in="path",
     *         description="Record ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Parameter(
     *         name="media",
     *         in="path",
     *         description="Media ID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="File deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found"
     *     )
     * )
     */
    public function deleteFile(Record $record, Media $media)
    {
        $this->handleMediaDelete($record , $media);

        return response()->noContent();
    }
}
