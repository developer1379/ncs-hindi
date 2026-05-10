<?php

namespace App\Actions\Settings;

use App\Services\SettingService;
use Illuminate\Http\Request;

class UpdatePageSettingsAction
{
    public function __construct(protected SettingService $settingService) {}

    public function handle(Request $request): void
    {
        $keys = [
            'home_page_title',
            'about_page_title',
            'contact_page_title',
            'faq_page_title',
            'faq_page_intro',
            'faq_page_content',
            'legal_page_title',
            'legal_page_intro',
            'legal_page_content',
            'global_license_text',
        ];

        foreach ($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }
    }
}
