<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BootstrapTest extends TestCase
{
    public function test_health_endpoint_and_auth_redirects(): void
    {
        $this->get('/up')->assertOk();
        $this->get('/home')->assertRedirect(route('login'));
        $this->getJson('/api/user')->assertUnauthorized();

        $user = new User;
        $user->id = 1;
        $this->actingAs($user)->get('/login')->assertRedirect('/home');
    }

    public function test_api_group_still_limits_requests(): void
    {
        Route::middleware('api')->get('/api/bootstrap-throttle-test', fn () => response()->noContent());

        for ($request = 0; $request < 60; $request++) {
            $this->getJson('/api/bootstrap-throttle-test')->assertNoContent();
        }

        $this->getJson('/api/bootstrap-throttle-test')->assertStatus(429);
    }

    public function test_web_posts_require_csrf_outside_testing_environment(): void
    {
        $this->app['env'] = 'production';

        try {
            $this->post('/login', [])->assertStatus(419);
        } finally {
            $this->app['env'] = 'testing';
        }
    }
}
