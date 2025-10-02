<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Nette\Utils\Arrays;

class NotificationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'message' => 'required|string',
        ]);

        $data = Arrays::mergeTree([
            'sender_email' => config('mail.from.address'),
        ], $validated);

        $notification = Notification::create($data);

        return response()->json($notification, 201);
    }

    public function index()
    {
        return response()->json(Notification::all());
    }
}
