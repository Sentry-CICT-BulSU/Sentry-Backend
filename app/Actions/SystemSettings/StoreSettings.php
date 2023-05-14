<?php

namespace App\Actions\SystemSettings;

use App\Models\SystemSettings;
use Illuminate\Http\Request;

class StoreSettings
{
    public static function handle(Request $request)
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
        $sys_settings = SystemSettings::first();
        if (is_null($sys_settings)) {
            return SystemSettings::create($data);
        }
        $sys_settings->update($data);

        return $sys_settings;
    }
}
