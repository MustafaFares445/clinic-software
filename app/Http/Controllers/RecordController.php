<?php

namespace App\Http\Controllers;

use App\Enums\RecordIllsTypes;
use App\Enums\RecordMedicinesTypes;
use App\Http\Requests\RecordRequest;
use App\Http\Resources\RecordResource;
use App\Models\Record;
use App\Models\Reservation;
use App\Services\MediaService;
use App\Services\RecordService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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
     * Store a newly created resource in storage.
     */
    public function store(RecordRequest $request): RecordResource
    {
        $record = Record::query()->create($request->validated());

        if ($request->has('medicines'))
            $this->recordService->insertMedicines($record , $request->validated('medicines'));

        if ($request->has('transientMedicines'))
            $this->recordService->insertMedicines($record , $request->validated('transientMedicines') , RecordMedicinesTypes::TRANSIENT);

        if ($request->has('ills'))
            $this->recordService->insertIlls($record , $request->validated('ills'));

        if ($request->has('transientIlls'))
            $this->recordService->insertIlls($record , $request->validated('transientIlls') , RecordIllsTypes::TRANSIENT);

        if ($request->has('doctorsIds'))
            $record->doctors()->sync($request->validated('doctorsIds'));

        $this->mediaService->handleMediaUpload($record , $request , Record::$mediaCollection);

        return RecordResource::make($record->load(['media' , 'reservation' , 'ills' , 'medicines' , 'doctors']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Record $record): RecordResource
    {
        return RecordResource::make($record->load(['media' , 'reservation' , 'ills' , 'transientIlls' , 'transientMedicines' , 'medicines' , 'doctors']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Record $record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Record $record): Response
    {
        $record->delete();
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore(Record $record): Response
    {
        $record->restore();
        return response()->noContent();
    }
}
