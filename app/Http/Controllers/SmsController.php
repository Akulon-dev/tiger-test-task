<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\SmsService;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="SMS Proxy API",
 *     version="1.0.0",
 *     description="API для проксирования запросов к postback-sms.com. По-умолчанию используется токен из ТЗ"
 * )
 * @OA\Server(
 *     url="http://php.mshome.net:8000",
 *     description="Локальный сервер"
 * )
 */
class SmsController extends Controller
{
    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * @OA\Get(
     *     path="/api/get-number",
     *     summary="Получить номер телефона. По-умолчанию используется токен из ТЗ",
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="service",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="rent_time",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="number", type="string"),
     *             @OA\Property(property="activation", type="string"),
     *             @OA\Property(property="cost", type="number")
     *         )
     *     )
     * )
     */
    public function getNumber(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'country' => 'required|string',
            'service' => 'required|string',
            'token' => 'nullable|string',
            'rent_time' => 'nullable|integer'
        ]);

        return response()->json($this->smsService->requestNumber(
            $validated['country'],
            $validated['service'],
            $validated['rent_time'] ?? null,
            $validated['token'] ?? null
        ));
    }

    /**
     * @OA\Get(
     *     path="/api/get-sms",
     *     summary="Получить SMS по activation ID. По-умолчанию используется токен из ТЗ",
     *     @OA\Parameter(
     *         name="activation",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="sms", type="string")
     *         )
     *     )
     * )
     */
    public function getSms(Request $request): JsonResponse
    {
        $validated = $request->validate(['activation' => 'required|string','token' => 'nullable|string']);
        return response()->json($this->smsService->fetchSms($validated['activation'],$validated['token'] ?? null));
    }

    /**
     * @OA\Get(
     *     path="/api/cancel-number",
     *     summary="Отменить номер. По-умолчанию используется токен из ТЗ",
     *     @OA\Parameter(
     *         name="activation",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Номер отменен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="activation", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     )
     * )
     */
    public function cancelNumber(Request $request): JsonResponse
    {
        $validated = $request->validate(['activation' => 'required|string','token' => 'nullable|string']);
        return response()->json($this->smsService->revokeNumber($validated['activation'],$validated['token'] ?? null));
    }

    /**
     * @OA\Get(
     *     path="/api/get-status",
     *     summary="Получить статус активации. По-умолчанию используется токен из ТЗ",
     *     @OA\Parameter(
     *         name="activation",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Текущий статус активации",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="count_sms", type="integer")
     *         )
     *     )
     * )
     */
    public function getStatus(Request $request): JsonResponse
    {
        $validated = $request->validate(['activation' => 'required|string','token' => 'nullable|string']);
        return response()->json($this->smsService->checkStatus($validated['activation'],$validated['token'] ?? null));
    }
}
