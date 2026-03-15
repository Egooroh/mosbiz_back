<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Carbon\Carbon;

/**
 * @group Настройки сайта
 *
 * API для получения глобальных данных интерфейса (шапка, баннер, статистика).
 */
class SettingController extends Controller
{
    /**
     * Получить настройки главного экрана
    * Возвращает глобальные данные интерфейса сайта: логотип, контактный телефон,
    * список социальных сетей, тексты главного баннера и статистические карточки.
    * Эти данные используются для отображения главной страницы сайта.
    *
    * @response 200 {
    *   "success": true,
    *   "data": {
    *     "id": 1,
    *     "logo": "/storage/logo.png",
    *     "phone": "+7 (999) 123-45-67",
    *     "socials": [
    *       {
    *         "name": "telegram",
    *         "url": "https://t.me/company"
    *       }
    *     ],
    *     "banner_title": "Программы и инструменты для развития бизнеса",
    *     "banner_subtitle": "от Департамента предпринимательства и инновационного развития Москвы",
    *     "stats": [
    *       {
    *         "title": "105 млрд ₽",
    *         "description": "Направлено на поддержку "
    *       }
    *     ]
    *   }
    * }
    *
    * @response 404 {
    *   "success": false,
    *   "message": "Настройки сайта еще не заполнены в админ-панели"
    * }
    */
    public function index()
    {
        $settings = SiteSetting::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'Настройки сайта еще не заполнены в админ-панели'
            ], 404);
        }
        
        $socials = is_array($settings->social_links) 
            ? collect($settings->social_links)->map(function ($link) {
                return [
                    'icon' => isset($link['icon']) ? asset('storage/' . $link['icon']) : null,
                    'url'  => $link['url'] ?? null,
                ];
            })->toArray() 
            : [];

        $statistics = is_array($settings->statistics)
            ? collect($settings->statistics)->map(function ($statistics) {
                return [
                    'title' => $statistics['value'] ?? '',
                    'description' => $statistics['description'] ?? '',
                ];
            })->toArray()
            : [];

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $settings->id,
                'logo' => $settings->header_logo ? asset('storage/' . $settings->header_logo) : null,
                'phone' => $settings->phone,
                'socials' => $socials,
                'banner_title' => $settings->hero_title,
                'banner_subtitle' => $settings->hero_subtitle,
                'statistics' => $statistics,
            ]
        ]);
    }
}
