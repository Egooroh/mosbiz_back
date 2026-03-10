<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Обратная связь
 *
 * API для приема заявок от клиентов с сайта.
 */
class FeedbackController extends Controller
{
    /**
     * Отправить заявку (Форма)
     *
     * Создает новую заявку в базе данных. В админ-панели она появится со статусом "Новая".
     *
     * @bodyParam name string required Имя клиента. Example: Иван Иванов
     * @bodyParam phone string required Контактный телефон. Example: +7 (999) 123-45-67
     * @bodyParam email string required Электронная почта. Example: ivan@gmail.com
     * @bodyParam inn string ИНН организации (необязательно). Example: 7712345678
     *
     * @response 201 {
     * "success": true,
     * "message": "Заявка успешно получена",
     * "id": 5
     * }
     * @response 422 {
     * "success": false,
     * "errors": {
     * "email": ["Поле email обязательно для заполнения."]
     * }
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => 'required|string|max:255', 
           'phone' => 'required|string|max:25', 
           'email' => 'required|email|max:255', 
           'inn' => 'nullable|string|max:12', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $feedback = Feedback::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Заявка успешно получена',
            'id' => $feedback->id
        ], 201);
    }
}
