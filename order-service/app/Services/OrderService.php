<?php
namespace App\Services;

use App\Events\OrderCreated;
use App\Exceptions\UserNotFoundException;
use App\Models\Order;
use App\Services\UserServiceClient;

class OrderService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private UserServiceClient $userServiceClient
    ) {}
    public function createOrder(array $data): Order
    {
        $user = $this->userServiceClient->findUser(
            $data['user_id']
        );

        if ($user === null) {
            throw new UserNotFoundException();
        }
        $order = Order::create($data);
        event(new OrderCreated($order));

        return $order;
    }
}
