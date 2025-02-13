<?php

namespace App\Http\Controllers;

use App\Models\Ill;
use App\Models\Patient;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Overview",
 *     description="API Endpoints for dashboard overview statistics"
 * )
 */
class OverviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/overview/patients/gender/count",
     *     summary="Get patient count by gender",
     *     tags={"Overview"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="malesCount", type="integer", example=150),
     *             @OA\Property(property="femalesCount", type="integer", example=200)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function patientsGenderCount(): JsonResponse
    {
        $malesCount =  Patient::query()->where('gender' , 'male')->count();
        $femalesCount = Patient::query()->where('gender' , 'female')->count();

        return response()->json([
           'malesCount' => $malesCount,
           'femalesCount' => $femalesCount
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/ills/count",
     *     summary="Get illness statistics",
     *     tags={"Overview"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="string", format="uuid"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="records_count", type="integer")
     *                 )
     *             ),
     *             @OA\Property(property="totalCount", type="integer")
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function illsCount(): JsonResponse
    {
        $ills = Ill::query()
            ->select('id', 'name')
            ->whereRelation('records' , 'clinic_id' , Auth::user()->clinic_id)
            ->withCount('records')
            ->get();

        return response()->json([
            'data' => $ills,
            'totalCount' => $ills->sum('records_count')
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/records/count",
     *     summary="Get records count within date range",
     *     tags={"Overview"},
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date for records count (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date for records count (Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="count", type="integer", example=50)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function recordsCount(Request $request): JsonResponse
    {
        $count = Record::query()
            ->whereDate('dateTime' , '>=' , $request->input('startDate') ?? Carbon::now()->firstOfMonth())
            ->whereDate('dateTime' , '<=' ,$request->input('endDate') ?? Carbon::now()->lastOfMonth())
            ->count();

        return response()->json([
            'count' => $count
        ]);
    }
}
