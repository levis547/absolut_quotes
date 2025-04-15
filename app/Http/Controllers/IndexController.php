<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\User_visit;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use GuzzleHttp\Client;
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
        $ipAddress = $request->ip();

        // Получаем данные о браузере
        $agent = new Agent();
        $browser = $agent->browser();

        // Получаем местоположение по IP через API 2ip.ru
        $address = $this->getLocationByIp($ipAddress);

        // Сохраняем данные в базе данных
        User_visit::create([
            'ip_address' => $ipAddress,  // Сохраняем IP-адрес
            'browser' => $browser,       // Сохраняем информацию о браузере
            'address' => $address,       // Сохраняем адрес по IP
        ]);

        return response()->json(['message' => 'Visit tracked']);
    }

    private function getLocationByIp($ipAddress)
    {
        // Используем API для получения местоположения по IP
        $client = new Client();
        $apiKey = 'qa138aeews6spzgr';  // Замените на ваш API-ключ
        $url = "https://2ip.ru/api/geo/?ip={$ipAddress}&token={$apiKey}";

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            // Проверяем, есть ли данные о местоположении
            if (isset($data['city']) && isset($data['country'])) {
                return $data['city'] . ', ' . $data['country'];
            } else {
                return 'Unknown location';
            }
        } catch (\Exception $e) {
            // В случае ошибки возвращаем 'Unknown location'
            return 'Unknown location';
        }
    }
}
