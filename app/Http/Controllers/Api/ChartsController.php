<?php

namespace App\Http\Controllers\Api;

use App\Actions\Charts\AdminChart;
use App\Http\Controllers\Controller;
use App\Models\Attendances;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public function index(Request $request)
    {
        return match ($request->user()->type) {
            USER::TYPES[User::ADMIN] => self::admin($request),
            USER::TYPES[User::ATTENDANCE_CHECKER] => self::attendance($request),
            USER::TYPES[User::FACULTY] => self::faculty($request),
            default => ''
        };
    }
    private static function admin(Request $request): JsonResponse
    {
        return AdminChart::getChart();
    }
    private static function attendance(Request $request)
    {
        return AdminChart::getChart();
    }
    private static function faculty(Request $request)
    {

    }
}
