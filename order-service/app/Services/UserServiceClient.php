<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserServiceClient
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function findUser(int $userId): ?array
    {
        // dd(config('services.user_service.url') . "/api/users/{$userId}");
        $response = Http::timeout(3)
            ->get(
                config('services.user_service.url') . "/api/users/{$userId}"
            );

        if ($response->status() === 404) {
            return null;
        }

        $response->throw();

        return $response->json();
    }
}
