<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderType;
use App\Models\Partnership;
use App\Models\User;
use App\States\Assigned;
use App\States\Created;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $orderType = OrderType::query()->inRandomOrder()->first() ?? OrderType::factory()->create();
        $partnership = Partnership::query()->inRandomOrder()->first() ?? Partnership::factory()->create();
        $manager = User::factory()->for($partnership)->create();

        return [
            'type_id' => $orderType->id,
            'partnership_id' => $partnership->id,
            'user_id' => $manager->id,
            'description' => $this->faker->paragraph(2),
            'date' => $this->faker->dateTimeBetween('+1 day', '+1 month')->format('Y-m-d'),
            'address' => $this->faker->address(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(Order::STATUSES),
        ];
    }

    public function statusCreated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Created::$name,
        ]);
    }

    public function statusAssigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Assigned::$name,
        ]);
    }

    public function statusCompleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Completed::$name,
        ]);
    }
}
