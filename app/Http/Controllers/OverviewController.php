<?php

namespace App\Http\Controllers;

use App\Enums\RecordTypes;
use App\Enums\ReservationTypes;
use App\Http\Resources\IllResource;
use App\Models\BillingTransaction;
use App\Models\Ill;
use App\Models\MedicalCase;
use App\Models\Patient;
use App\Models\Record;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
    public function patientsGenderCount(Request $request): JsonResponse
    {
        $request->validate([
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)]
        ]);

        $year = $request->input('year', now()->year);

        $malesCount = Patient::query()
            ->where('gender', 'male')
            ->whereHas('medicalCases', fn($query) => $query->whereYear('date', $year))
            ->count();

        $femalesCount = Patient::query()
            ->where('gender', 'female')
            ->whereHas('medicalCases', fn($query) => $query->whereYear('date', $year))
            ->count();

        return response()->json([
            'malesCount' => $malesCount,
            'femalesCount' => $femalesCount,
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/overview/medical/cases/count",
     *     summary="Get medical cases count within date range",
     *     tags={"Overview"},
     *
     *     @OA\Parameter(
     *         name="startDate",
     *         in="query",
     *         description="Start date for medical cases count (Y-m-d)",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="date")
     *     ),
     *
     *     @OA\Parameter(
     *         name="endDate",
     *         in="query",
     *         description="End date for medical cases count (Y-m-d)",
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
    public function medicalCasesCount(Request $request): JsonResponse
    {
        $count = MedicalCase::query()
            ->whereDate('date', '>=', $request->input('startDate') ?? Carbon::now()->firstOfMonth())
            ->whereDate('date', '<=', $request->input('endDate') ?? Carbon::now()->lastOfMonth())
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
        $reservationQuery = Reservation::query();

        return response()->json([
            'reservationsCount' => Reservation::query()->count(),
            'surgeryCount' => $reservationQuery->clone()->where('type', ReservationTypes::SURGERY->value)->count(),
            'appointmentCount' => $reservationQuery->clone()->where('type', ReservationTypes::APPOINTMENT->value)->count(),
            'inspectionCount' => $reservationQuery->clone()->where('type', ReservationTypes::INSPECTION->value)->count(),
        ]);
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
     *         @OA\Schema(type="string", enum={"in", "out"}, default="in")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="totalTransactions", type="number", format="float", example=5000.50),
     *             @OA\Property(property="totalInMonth", type="number", format="float", example=1500.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $query = BillingTransaction::query();

        return response()->json([
            'totalTransactions' => (float) $query->sum('amount'),
            'totalInMonth' => (float) $query
                ->whereMonth('created_at' , Carbon::now()->month)
                ->sum('amount'),
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
        $request->validate([
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)]
        ]);

        $year = $request->input('year', now()->year);
        $date = \Carbon\Carbon::create($year, 12, 31);

        return response()->json([
            'adults' => Patient::query()
                ->whereHas('records', fn($query) => $query->whereYear('dateTime', $year))
                ->whereRaw('TIMESTAMPDIFF(YEAR, birth, ?) >= 18', [$date])
                ->count(),

            'children' => Patient::query()
                ->whereHas('records', fn($query) => $query->whereYear('dateTime', $year))
                ->whereRaw('TIMESTAMPDIFF(YEAR, birth, ?) < 18', [$date])
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
     *         @OA\Schema(type="integer", default="2025")
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of transaction (in/out)",
     *         required=false,
     *         @OA\Schema(type="string", enum={"in", "out"}, default="in")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="labels", type="array",
     *                 @OA\Items(type="string", example="Jan")
     *             ),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="number", format="float", example=1500.50)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function billingChartStatistics(Request $request): JsonResponse
    {
        $request->validate([
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        ]);

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
