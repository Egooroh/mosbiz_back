<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamMember;

/**
 * @group Команда (Департамент)
 *
 * API для вывода списка сотрудников и руководителей.
 */
class TeamController extends Controller
{
    /**
     * Получить состав команды
    *
    * Возвращает список сотрудников компании. Ответ разделен на две части:
    * руководитель департамента (`head`) и остальные сотрудники (`members`).
    * Это сделано для удобного отображения на фронтенде.
    *
    * @response 200 {
    *   "success": true,
    *   "data": {
    *     "head": {
    *       "id": 1,
    *       "name": "Кострома Кристина Геннадьевна",
    *       "position": "Руководитель Департамента предпринимательства и инновационного развития города Москвы",
    *       "description": "Отвечает за стратегию и управление командой.",
    *       "is_head": true,
    *       "photo_url": "https://example.com/storage/team/ivan.jpg"
    *     },
    *     "members": [
    *       {
    *         "id": 2,
    *         "name": "Дерюгин Александр Владимирович",
    *         "position": "Заместитель руководителя Департамента",
    *         "description": "Координирует работу проектов.",
    *         "is_head": false,
    *         "photo_url": "https://example.com/storage/team/petr.jpg"
    *       }
    *     ]
    *   }
    * }
    *
    * @response 404 {
    *   "success": false,
    *   "message": "Сотрудники не найдены"
    * }
    */
    public function index()
    {
        $allMembers = TeamMember::all()->map(function ($member) {
            return [
                'id' => $member->id,
                'name' => $member->name,
                'position' => $member->position,
                'description' => $member->description,
                'is_head' => (bool)$member->is_head,
                'photo_url' => $member->photo_path ? asset('storage/' . $member->photo_path) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'head' => $allMembers->where('is_head', true)->first(),
                'members' => $allMembers->where('is_head', false)->values(),
            ]
        ]);
    }
}
