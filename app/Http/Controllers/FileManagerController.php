<?php

namespace App\Http\Controllers;

use App\Http\Requests\MediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\FileManager;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="File Manager",
 *     description="API Endpoints for file management operations"
 * )
 */
class FileManagerController extends Controller
{
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaService();
    }

    /**
     * @OA\Get(
     *     path="/api/file-manager",
     *     summary="List all media files",
     *     tags={"File Manager"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="collection",
     *         in="query",
     *         description="Filter by collection name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of media files",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/MediaResource")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return MediaResource::collection(
            Media::query()
                ->when($request->has('collection'), fn($query) => $query->where('collection_name', $request->input('collection')))
                ->cursorPaginate()
        );
    }

    /**
     * @OA\Get(
     *     path="/api/file-manager/medical-collections",
     *     summary="Get list of medical collections",
     *     tags={"File Manager"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="List of medical collections",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="string")
     *         )
     *     )
     * )
     */
    public function getMedicalCollections(): JsonResponse
    {
        return response()->json(MediaService::$medicalMediaCollections);
    }

    /**
     * @OA\Post(
     *     path="/api/file-manager",
     *     summary="Upload new file(s)",
     *     tags={"File Manager"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                      format="binary",
     *                     description="Files to upload"
     *                 ),
     *                 @OA\Property(
     *                     property="collection",
     *                     type="string",
     *                     description="Collection name for the files"
     *                 )
     *             )
     *         )
     *     ),
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
    public function store(MediaRequest $request): MediaResource
    {
        $fileManager = FileManager::query()->create(['name' => $request->file()->getClientOriginalName()]);
        return MediaResource::make($this->mediaService->handleMediaUpload($fileManager, $request->file(), $request->input('collection')));
    }

    /**
     * @OA\Delete(
     *     path="/api/file-manager/{media}",
     *     summary="Delete a media file",
     *     tags={"File Manager"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="media",
     *         in="path",
     *         required=true,
     *         description="Media ID",
     *         @OA\Schema(type="integer")
     *     ),
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
    public function delete(Media $media): Response
    {
        $media->delete();
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/file-manager/{media}/download",
     *     summary="Download a media file",
     *     tags={"File Manager"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\Parameter(
     *         name="media",
     *         in="path",
     *         required=true,
     *         description="Media ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File download",
     *         @OA\Header(
     *             header="Content-Type",
     *             description="File MIME type",
     *             @OA\Schema(type="string")
     *         ),
     *         @OA\Header(
     *             header="Content-Disposition",
     *             description="File attachment information",
     *             @OA\Schema(type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="File not found"
     *     )
     * )
     */
    public function download(Media $media): BinaryFileResponse
    {
        return response()->download($media->getPath(), $media->file_name);
    }
}
