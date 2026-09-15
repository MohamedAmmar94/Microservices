<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class UserServiceClient {
    /**
     * Create a new class instance.
     */
    public function __construct() {
        //
    }
    public function findUser(int $userId): ?array {
        $response = Http::timeout(9)
            ->get(
                config('services.user_service.url') . "/api/users/{$userId}"
            );
        // dd($response);
        if ($response->status() === 404) {
            return null;
        }

        $response->throw();

        return $response->json();
    }
}
