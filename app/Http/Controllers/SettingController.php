<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'currency' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'shipping_cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'order_prefix' => 'nullable|string|max:10',
            'invoice_prefix' => 'nullable|string|max:10',
            'ticket_prefix' => 'nullable|string|max:10',
            'maintenance_mode' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            $this->updateSetting('logo', $logoPath);
        }
        
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $this->updateSetting('favicon', $faviconPath);
        }

        // Update text settings
        foreach ($request->except(['_token', 'logo', 'favicon']) as $key => $value) {
            $this->updateSetting($key, $value);
        }

        // Clear settings cache
        Cache::forget('settings');

        // If maintenance mode changed, update application state
        if ($request->has('maintenance_mode')) {
            if ($request->maintenance_mode) {
                Artisan::call('down');
            } else {
                Artisan::call('up');
            }
        }

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully.');
    }
    
    /**
     * Update or create a setting.
     */
    private function updateSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
    
    /**
     * Clear application cache.
     */
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        return back()->with('success', 'Application cache cleared successfully.');
    }
}