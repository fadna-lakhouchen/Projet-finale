<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(int $userId): JsonResponse
    {
        $user = User::with('notifications')->findOrFail($userId);

        return response()->json([
            'status' => 'success',
            'data' => $user->notifications
        ]);
    }
}
