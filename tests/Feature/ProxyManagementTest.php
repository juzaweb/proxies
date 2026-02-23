<?php

namespace Juzaweb\Modules\Proxies\Tests\Feature;

use Juzaweb\Modules\Proxies\Models\Proxy;
use Juzaweb\Modules\Proxies\Tests\TestCase;
use Juzaweb\Modules\Core\Models\User;
use Illuminate\Support\Facades\Gate;

class ProxyManagementTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Gate::before(function () {
            return true;
        });

        \Illuminate\Support\Facades\Schema::table('users', function ($table) {
            $table->boolean('is_admin')->default(0);
        });

        $this->user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $this->actingAs($this->user);
    }

    public function test_index_page()
    {
        $response = $this->get(route('admin.proxies.index'));

        $response->assertStatus(200);
        $response->assertSee('Proxies');
    }

    public function test_create_page()
    {
        $response = $this->get(route('admin.proxies.create'));

        $response->assertStatus(200);
        $response->assertSee('IP Address');
    }

    public function test_store_proxy()
    {
        $data = [
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
            'type' => 'ipv4',
            'active' => 1,
        ];

        $response = $this->post(route('admin.proxies.store'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.proxies.index'));

        $this->assertDatabaseHas('jwpr_proxies', [
            'ip' => '127.0.0.1',
            'port' => '8080',
        ]);
    }

    public function test_edit_page()
    {
        $proxy = Proxy::create([
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
        ]);

        $response = $this->get(route('admin.proxies.edit', [$proxy->id]));

        $response->assertStatus(200);
        $response->assertSee($proxy->ip);
    }

    public function test_update_proxy()
    {
        $proxy = Proxy::create([
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
        ]);

        $data = [
            'ip' => '192.168.1.1',
            'port' => '8081',
            'protocol' => 'https',
        ];

        $response = $this->put(route('admin.proxies.update', [$proxy->id]), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.proxies.index'));

        $this->assertDatabaseHas('jwpr_proxies', [
            'id' => $proxy->id,
            'ip' => '192.168.1.1',
            'port' => '8081',
            'protocol' => 'https',
        ]);
    }

    public function test_delete_proxy()
    {
        $proxy = Proxy::create([
            'ip' => '127.0.0.1',
            'port' => '8080',
            'protocol' => 'http',
        ]);

        $response = $this->delete(route('admin.proxies.destroy', [$proxy->id]));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('jwpr_proxies', [
            'id' => $proxy->id,
        ]);
    }
}
