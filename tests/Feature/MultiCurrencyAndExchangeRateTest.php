<?php

namespace Tests\Feature;

use App\Helpers\CurrencyHelper;
use App\Livewire\WorkshopSettings;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MultiCurrencyAndExchangeRateTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Taller Central Multimoneda',
            'slug' => 'taller-central-multimoneda',
            'admin_name' => 'Carlos López',
            'email' => 'taller@multimoneda.com',
            'phone' => '12345678',
            'address' => 'Zona 10, Ciudad de Guatemala',
            'users_limit' => 10,
            'exchange_rate' => 7.8000,
            'default_currency' => 'GTQ',
            'is_active' => true,
        ]);

        $this->adminUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'role' => 'admin',
        ]);
    }

    public function test_currency_helper_conversions_and_formatting(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Tasa por defecto = 7.80
        $this->assertEquals(7.80, CurrencyHelper::getExchangeRate($this->tenant));

        // 2. Conversión GTQ a USD: 780 GTQ / 7.80 = 100 USD
        $usd = CurrencyHelper::convertFromGtq(780, CurrencyHelper::CURRENCY_USD, 7.80);
        $this->assertEquals(100.00, $usd);

        // 3. Conversión USD a GTQ: 100 USD * 7.80 = 780 GTQ
        $gtq = CurrencyHelper::convertToGtq(100, 7.80);
        $this->assertEquals(780.00, $gtq);

        // 4. Formato Dual
        CurrencyHelper::setActiveCurrency('GTQ');
        $dualGtq = CurrencyHelper::formatDual(780);
        $this->assertStringContainsString('Q 780.00', $dualGtq);
        $this->assertStringContainsString('US$ 100.00', $dualGtq);

        // 5. Breakdown para dashboard
        $breakdown = CurrencyHelper::breakdown(780);
        $this->assertEquals('Q 780.00', $breakdown['primary']);
        $this->assertEquals('≈ US$ 100.00', $breakdown['secondary']);
    }

    public function test_currency_switch_route_updates_session(): void
    {
        $this->actingAs($this->adminUser);

        // Cambiar a USD
        $response = $this->get(route('currency.switch', 'USD'));
        $response->assertStatus(302);
        $this->assertEquals('USD', CurrencyHelper::getActiveCurrency());

        // Cambiar a GTQ
        $response = $this->get(route('currency.switch', 'GTQ'));
        $response->assertStatus(302);
        $this->assertEquals('GTQ', CurrencyHelper::getActiveCurrency());
    }

    public function test_workshop_admin_can_update_exchange_rate_and_default_currency(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(WorkshopSettings::class)
            ->set('activeTab', 'general')
            ->set('name', 'Taller Central Modificado')
            ->set('admin_name', 'Carlos López')
            ->set('email', 'taller@multimoneda.com')
            ->set('phone', '55551234')
            ->set('address', 'Dirección actualizada')
            ->set('exchange_rate', 7.85)
            ->set('default_currency', 'USD')
            ->call('save')
            ->assertDispatched('notify');

        $this->tenant->refresh();
        $this->assertEquals(7.8500, (float) $this->tenant->exchange_rate);
        $this->assertEquals('USD', $this->tenant->default_currency);
    }
}
