<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderType;
use App\Models\Partnership;
use App\Models\User;
use App\Models\Worker;
use App\States\Assigned;
use App\States\Created;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            OrderTypeSeeder::class,
        ]);

        $partnerships = Partnership::factory(5)->create();

        User::factory(10)->create()->each(function ($user) use ($partnerships) {
            $user->partnership_id = $partnerships->random()->id;
            $user->save();
        });

        $workers = Worker::factory(20)->create();
        $orderTypes = OrderType::all();

        Order::factory(50)->create()->each(function($order) use ($workers, $orderTypes) {
            if (rand(0,1)) {
                $workerToAssign = $workers->random();
                $isExcluded = $workerToAssign->excludedOrderTypes()->where('order_type_id', $order->type_id)->exists();
                if (!$isExcluded) {
                    $order->workers()->attach($workerToAssign->id, ['amount' => rand(1000, 5000) / 10]);
                    if ($order->status == Created::$name) {
                        $order->assign();
                    }
                }
            }
        });

        $workers->each(function ($worker) use ($orderTypes) {
            if (rand(0, 2) == 1) {
                $typesToExclude = $orderTypes->random(rand(1, $orderTypes->count() > 1 ? 2 : 1));
                $worker->excludedOrderTypes()->attach($typesToExclude->pluck('id')->toArray());
            }
        });
    }
}
