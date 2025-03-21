<?php

namespace App\Http\Controllers;

use App\Enums\RecordTypes;
use App\Http\Resources\IllResource;
use App\Models\BillingTransaction;
use App\Models\Ill;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Overview",
 *     description="API Endpoints for dashboard overview statistics"
 * )
 */
final class OverviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/overview/patients/gender/count",
     *     summary="Get patient count by gender",
     *     tags={"Overview"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="malesCount", type="integer", example=150),
     *             @OA\Property(property="femalesCount", type="integer", example=200)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function patientsGenderCount(): JsonResponse
    {
        $malesCount = Patient::query()->where('gender', 'male')->count();
        $femalesCount = Patient::query()->where('gender', 'female')->count();

        return response()->json([
            'malesCount' => $malesCount,
            'femalesCount' => $femalesCount,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/ills/count",
     *     summary="Get illness statistics",
     *     tags={"Overview"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
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
            ->whereRelation('records', 'clinic_id', Auth::user()->clinic_id)
            ->withCount('records')
            ->get();

        return response()->json([
            'data' => $ills,
            'totalCount' => $ills->sum('records_count'),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/records/count",
     *     summary="Get records count within date range",
     *     tags={"Overview"},
     *
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date for records count (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date for records count (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="count", type="integer", example=50)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function recordsCount(Request $request): JsonResponse
    {
        $count = Record::query()
            ->whereDate('dateTime', '>=', $request->input('startDate') ?? Carbon::now()->firstOfMonth())
            ->whereDate('dateTime', '<=', $request->input('endDate') ?? Carbon::now()->lastOfMonth())
            ->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/general-statistics",
     *     summary="Get general statistics",
     *     tags={"Overview"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="reservationsCount", type="integer", example=100),
     *             @OA\Property(property="surgeryCount", type="integer", example=30),
     *             @OA\Property(property="appointmentCount", type="integer", example=50),
     *             @OA\Property(property="inspectionCount", type="integer", example=20)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function generalStatistics(): JsonResponse
    {
        $recordQuery = Record::query();

        return response()->json([
            'reservationsCount' => Reservation::query()->count(),
            'surgeryCount' => $recordQuery->clone()->where('type', RecordTypes::SURGERY)->count(),
            'appointmentCount' => $recordQuery->clone()->where('type', RecordTypes::APPOINTMENT)->count(),
            'inspectionCount' => $recordQuery->clone()->where('type', RecordTypes::INSPECTION)->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/top-ills",
     *     summary="Get top 5 illnesses",
     *     tags={"Overview"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(
     *
     *                     @OA\Property(property="id", type="string", format="uuid"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="records_count", type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function topIlls(): AnonymousResourceCollection
    {
        $ills = Ill::query()
            ->whereRelation('records', 'clinic_id', Auth::user()->clinic_id)
            ->select('id', 'name')
            ->withCount('records')
            ->orderBy('records_count', 'desc')
            ->limit(5)
            ->get();

        return IllResource::collection($ills);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/patients/count",
     *     summary="Get patients count",
     *     tags={"Overview"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="count", type="integer", example=500),
     *             @OA\Property(property="currentMonth", type="integer", example=50)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function patientsCount(): JsonResponse
    {
        $patientsQuery = Patient::query();

        return response()->json([
            'count' => $patientsQuery->clone()->count(),
            'currentMonth' => $patientsQuery->clone()->whereMonth('created_at', Carbon::now()->month)->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/billing-statistics",
     *     summary="Get billing statistics",
     *     tags={"Overview"},
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of transaction (in/out)",
     *         required=false,
     *
     *         @OA\Schema(type="string", default="in")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="totalTransactions", type="integer", example=100),
     *             @OA\Property(property="totalType", type="number", format="float", example=5000.50)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $query = BillingTransaction::query();

        return response()->json([
            'totalTransactions' => $query->count(),
            'totalType' => $query->where('type' , $request->input('type' , 'in'))->sum('amount'),
        ]);
    }

       /**
     * @OA\Get(
     *     path="/api/overview/age-statistics",
     *     summary="Get patient age statistics",
     *     tags={"Overview"},
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Filter by year of records",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="adults", type="integer", example=300),
     *             @OA\Property(property="children", type="integer", example=200)
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function getAgeStatistics(Request $request): JsonResponse
    {
        $year = $request->input('year', Carbon::now()->year);

        return response()->json([
            'adults' => Patient::query()
                ->whereHas('records', fn($query) => $query->whereYear('dateTime', $year))
                ->where('age', '>=', 18)
                ->count(),
            'children' => Patient::query()
                ->whereHas('records', fn($query) => $query->whereYear('dateTime', $year))
                ->where('age', '<', 18)
                ->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/overview/chart/billing-statistics",
     *     summary="Get billing statistics by month for a given year",
     *     tags={"Overview"},
     *
     *     @OA\Parameter(
     *         name="year",
     *         in="query",
     *         description="Year for billing statistics",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default="current year")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="labels", type="array",
     *
     *                 @OA\Items(type="string", example="Jan")
     *             ),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(type="number", format="float", example=1500.50)
     *             )
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function billingChartStatistics(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);

        $monthlyTotals = BillingTransaction::query()
            ->whereYear('created_at', $year)
            ->get(['id' , 'amount' , 'created_at'])
            ->groupBy(fn($transaction) => $transaction->created_at->month)
            ->map(fn($transactions) => $transactions->sum('amount'));

        // Fill missing months with 0
        $monthlyTotals = collect(range(1, 12))->mapWithKeys(fn($month) => [
            $month => $monthlyTotals->get($month, 0)
        ]);

        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $monthlyTotals->values()->toArray(),
        ]);
    }
}
