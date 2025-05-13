<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Partnership;
use App\Models\OrderType;
use App\Models\Order;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Events\OrderStatusUpdated;
use App\States\Created;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected Partnership $partnership;
    protected OrderType $orderType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->partnership = Partnership::factory()->create();
        $this->manager = User::factory()->create(['partnership_id' => $this->partnership->id]);
        $this->orderType = OrderType::factory()->create(['name' => 'Test type']);
        Passport::actingAs($this->manager);
    }

    public function test_manager_can_create_order()
    {
        $orderData = [ /* ... order data ... */ ];
        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(201)
                 ->assertJsonPath('data.description', $orderData['description']);
        $this->assertDatabaseHas('orders', ['description' => $orderData['description']]);
    }

    public function test_order_status_update_send_websocker_event()
    {
        Event::fake();

        $order = Order::factory()->create(['user_id' => $this->manager->id, 'status' => Created::$name]);
        $newStatus = 'assigned';

        $response = $this->putJson("/api/orders/{$order->id}/status", ['status' => $newStatus]);
        $response->assertOk();

        Event::assertDispatched(OrderStatusUpdated::class, function ($event) use ($order, $newStatus) {
            return $event->order->id === $order->id && $event->order->status === $newStatus;
        });
    }
}