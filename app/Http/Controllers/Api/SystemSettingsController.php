<?php

namespace App\Http\Controllers\Api;

use App\Actions\SystemSettings\StoreSettings;
use App\Http\Controllers\Controller;
use App\Http\Resources\SystemSettingsResource;
use App\Models\SystemSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    public function index(): SystemSettingsResource
    {
        return new SystemSettingsResource(SystemSettings::first());
    }
    public function store(Request $request): SystemSettingsResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $sys_settings = StoreSettings::handle($request);
            DB::commit();
            return new SystemSettingsResource($sys_settings);
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json(
                ['message' => $err->getMessage()],
                $err->getCode() ?? 500
            );
        }
    }
}
