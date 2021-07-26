<?php

namespace Database\Factories;

use App\Models\Thread;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Thread::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();

            return [
                'user_id' => \App\Models\User::factory(),
                'channel_id' => \App\Models\Channel::factory(),
                'title' => $this->faker->sentence(),
                'body' => $this->faker->paragraph(),
                'visits' => 0,
                'slug' => Str::slug($title),
                'locked' => false
            ];

    }
}
