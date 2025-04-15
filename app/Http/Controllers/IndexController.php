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

        // Проверяем, есть ли уже этот IP в базе данных
        $existingVisit = User_visit::where('ip_address', $ipAddress)->first();

        if ($existingVisit) {
            // Если IP уже есть в базе данных, обновляем только поле updated_at
            $existingVisit->touch(); // Это обновляет поле updated_at

            // Возвращаем сохранённый адрес
            $address = $existingVisit->address;
        } else {
            // Если IP ещё нет в базе, получаем местоположение через API
            $address = $this->getLocationByIp($ipAddress);

            // Сохраняем данные в базе данных
            User_visit::create([
                'ip_address' => $ipAddress,  // Сохраняем IP-адрес
                'browser' => (new Agent())->browser(),  // Сохраняем информацию о браузере
                'address' => $address,       // Сохраняем адрес по IP
            ]);
        }

        return response()->json(['message' => 'Visit tracked', 'address' => $address]);
    }

    private function getLocationByIp($ipAddress)
    {
        // Используем API для получения местоположения по IP
        $client = new Client();
        $apiKey = 'qa138aeews6spzgr';  // Замените на ваш API-ключ
        $url = "https://api.2ip.io/{$ipAddress}?token={$apiKey}"; // Формируем URL с IP и токеном

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            // Проверяем, есть ли данные о местоположении
            if (isset($data['city']) && isset($data['country'])) {
                // Возвращаем город и страну
                return $data['city'] . ', ' . $data['country'];
            } else {
                // Если местоположение не найдено, выводим информацию о том, что не удалось найти
                return 'Location not found';
            }
        } catch (\Exception $e) {
            // В случае ошибки возвращаем 'Unknown location'
            return 'Unknown location';
        }
    }
}
