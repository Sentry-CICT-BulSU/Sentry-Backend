<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SystemSettingsResource;
use App\Models\SystemSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index(): SystemSettingsResource
    {
        return new SystemSettingsResource(SystemSettings::latest()->first());
    }
    public function store(Request $request): SystemSettingsResource
    {
        $data = $request->validate([
            'name' => ['nullable', 'bail', 'string', 'max:255'],
            'about' => ['nullable', 'bail', 'string', 'max:1000'],
            'icon' => ['nullable', 'bail', 'image'],
            'color' => ['nullable', 'bail', 'string', 'max:255']
        ]);
        if ($request->file('icon')) {
            $fileName = $request->file('icon')->storePublicly('sys_settings');
            if (!$fileName) {
                throw new \Exception('Failed to upload icon image', 500);
            }
            $data['icon'] = $fileName;
        }
        return new SystemSettingsResource(SystemSettings::create($data));
    }
}
