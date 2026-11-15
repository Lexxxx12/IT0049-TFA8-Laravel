<?php

namespace Tests\Feature;

use App\Models\CustomerAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class CustomerSessionSecurityTest extends TestCase
{
    use LazilyRefreshDatabase;

    private string $sessionDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionDirectory = storage_path('framework/testing/audit-sessions-'.bin2hex(random_bytes(6)));
        File::ensureDirectoryExists($this->sessionDirectory);
        config([
            'session.driver' => 'file',
            'session.files' => $this->sessionDirectory,
        ]);
        $this->app['session']->forgetDrivers();

        $this->beforeApplicationDestroyed(function (): void {
            File::deleteDirectory($this->sessionDirectory);
        });
    }

    private function resetHttpState(): void
    {
        $this->app['auth']->forgetGuards();
        $this->app['session']->forgetDrivers();
    }

    public function test_customer_logout_invalidates_the_previous_cookie_and_regenerates_csrf_token(): void
    {
        $account = CustomerAccount::factory()->create([
            'email' => 'audit-session-customer@example.com',
        ]);

        $login = $this->post('/customer/login', [
            'email' => $account->email,
            'password' => 'password123',
        ])->assertRedirect('/customer/account');
        $cookieName = config('session.cookie');
        $oldSessionId = $login->getCookie($cookieName)?->getValue();
        $oldCsrfToken = session()->token();
        $this->assertNotNull($oldSessionId);
        $this->assertFileExists($this->sessionDirectory.'/'.$oldSessionId);
        $this->assertStringContainsString(Auth::guard('customer')->getName(), File::get($this->sessionDirectory.'/'.$oldSessionId));
        $this->resetHttpState();

        $logout = $this->withCookie($cookieName, $oldSessionId)
            ->post('/customer/logout')
            ->assertRedirect('/customer/login');
        $newSessionId = $logout->getCookie($cookieName)?->getValue();
        $this->assertNotNull($newSessionId);
        $this->assertNotSame($oldSessionId, $newSessionId);
        $this->assertNotSame($oldCsrfToken, session()->token());
        $this->assertFileDoesNotExist($this->sessionDirectory.'/'.$oldSessionId);
        $this->resetHttpState();

        $this->withCookie($cookieName, $newSessionId)
            ->get('/customer/account')
            ->assertRedirect('/customer/login');
        $this->resetHttpState();
        $this->withCookie($cookieName, $oldSessionId)
            ->get('/customer/account')
            ->assertRedirect('/customer/login')
            ->assertDontSee('audit-session-customer@example.com');
    }

    public function test_customer_logout_preserves_staff_authentication_when_both_guards_are_authenticated(): void
    {
        $staff = User::factory()->create([
            'username' => 'audit-session-staff',
        ]);
        $account = CustomerAccount::factory()->create([
            'email' => 'audit-dual-guard@example.com',
        ]);
        $cookieName = config('session.cookie');

        $staffLogin = $this->post('/login', [
            'username' => $staff->username,
            'password' => 'password',
        ])->assertRedirect('/sales/create');
        $staffSessionId = $staffLogin->getCookie($cookieName)?->getValue();
        $this->assertNotNull($staffSessionId);
        $this->resetHttpState();

        $customerLogin = $this->withCookie($cookieName, $staffSessionId)
            ->post('/customer/login', [
                'email' => $account->email,
                'password' => 'password123',
            ])->assertRedirect('/customer/account');
        $authenticatedSessionId = $customerLogin->getCookie($cookieName)?->getValue();
        $this->assertNotNull($authenticatedSessionId);
        $this->resetHttpState();

        $logout = $this->withCookie($cookieName, $authenticatedSessionId)
            ->post('/customer/logout')
            ->assertRedirect('/customer/login');
        $staffOnlySessionId = $logout->getCookie($cookieName)?->getValue();
        $this->assertNotNull($staffOnlySessionId);
        $this->resetHttpState();

        $this->withCookie($cookieName, $staffOnlySessionId)
            ->get('/sales/create')
            ->assertOk();
        $this->resetHttpState();
        $this->withCookie($cookieName, $staffOnlySessionId)
            ->get('/customer/account')
            ->assertRedirect('/customer/login');
        $this->resetHttpState();
        $this->withCookie($cookieName, $authenticatedSessionId)
            ->get('/customer/account')
            ->assertRedirect('/customer/login');
    }
}
