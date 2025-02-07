<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\FileManager;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileManagerController extends Controller
{
    protected MediaService $mediaService;

    public function __construct()
    {
        $this->mediaService = new MediaService();
    }
    public function index(Request $request): AnonymousResourceCollection
    {
        return MediaResource::collection(
            Media::query()
                ->when($request->has('collection'), fn($query) => $query->where('collection_name' , $request->input('collection')))
                ->cursorPaginate()
        );
    }

    public function getCollections(): JsonResponse
    {
        return response()->json($this->mediaService->mediaCollections);
    }

    public function store(Request $request): Response
    {
       $this->mediaService->handleMediaUpload(new FileManager() , $request);
       return response()->noContent();
    }

    public function delete(Media $media): Response
    {
        $media->delete();
        return response()->noContent();
    }
}
