<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\User_visit;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
class IndexController extends Controller
{
    public function index(Request $request)
    {
        // Отслеживаем посещение перед рендерингом страницы
        $this->trackVisit($request);

        // Извлекаем все опубликованные цитаты
        $quotes = Quote::where('status', 1)->get();

        return view('welcome', compact('quotes'));
    }

    public function trackVisit(Request $request)
    {
        // Получаем IP-адрес
        $ipAddress = $request->ip();  // Получаем IP-адрес пользователя

        // Получаем данные о браузере
        $agent = new Agent();
        $browser = $agent->browser();

        // Сохраняем данные в базе данных
        User_visit::create([
            'ip_address' => $ipAddress,  // Сохраняем IP-адрес
            'browser' => $browser,       // Сохраняем информацию о браузере
        ]);

        return response()->json(['message' => 'Visit tracked']);
    }
}
