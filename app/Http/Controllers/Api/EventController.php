<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

/**
 * @group Мероприятия
 *
 * API для работы с расписанием мероприятий.
 */
class EventController extends Controller
{
    /**
     * Получить список мероприятий
     *
     * Возвращает список всех мероприятий, отсортированных по дате начала
     * (от ближайших к самым поздним). Каждое мероприятие содержит основную
     * информацию: название, даты проведения, категорию, описание и ссылку
     * на изображение.
     *
     * @response 200 {
     *   "success": true,
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "День без турникетов",
     *       "start_date": "2026-05-20",
     *       "end_date": "2026-05-26",
     *       "category": "Человеческий капитал и профнавигация",
     *       "description": "Бесплатные экскурсии для горожан на площадки высокотехнологичных предприятий. Возможность побывать в офисах крупных компаний, научных лабораториях и других интересных локациях",
     *       "image_url": "https://example.com/storage/events/event1.jpg"
     *     },
     *     {
     *       "id": 2,
     *       "title": "Московская неделя интерьера и дизайна",
     *       "start_date": "2026-05-28",
     *       "end_date": "",
     *       "category": "Инновации",
     *       "description": "Главное событие в индустрии интерьера и дизайна — выставочные площади и деловая программа",
     *       "image_url": "https://example.com/storage/events/event2.jpg"
     *     }
     *   ]
     * }
     *
     * @response 404 {
     *   "success": false,
     *   "message": "Мероприятия не найдены"
     * }
     */
    public function index()
    {
        $events = Event::orderBy('start_date', 'asc')->get()->map(function ($event){
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => $event->start_date ? Carbon::parse($event->start_date)->format('Y-m-d H:i:s') : null,
                'end_date' => $event->end_date ? Carbon::parse($event->end_date)->format('Y-m-d H:i:s') : null,
                'category' => $event->category,
                'description' => $event->description,
                'image_url' => $event->image_path ? asset('storage/' . $event->image_path) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
}
