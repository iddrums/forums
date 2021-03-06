<?php

namespace Tests;

use Throwable;
use App\Models\User;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void

    {
        parent::setUp();

      DB::statement('PRAGMA foreign_keys=on');

        $this->disableExceptionHandling();
    }

    protected function signIn($user = null)
    {
    //    $user = $user ?:create('App\User');
       $user = $user ?: User::factory()->create();

       $this->actingAs($user);

       return $this;
    }

    protected function disableExceptionHandling()

    {

        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct() {}
            public function report(Throwable $e) {}
            public function render($request, Throwable $e) {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
       $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);

       return $this;
    }
}
