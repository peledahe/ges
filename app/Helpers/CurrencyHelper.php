<?php

namespace App\Helpers;

use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CurrencyHelper
{
    public const CURRENCY_GTQ = 'GTQ';
    public const CURRENCY_USD = 'USD';

    public const SYMBOLS = [
        self::CURRENCY_GTQ => 'Q',
        self::CURRENCY_USD => 'US$',
    ];

    public const NAMES = [
        self::CURRENCY_GTQ => 'Quetzales (Q)',
        self::CURRENCY_USD => 'Dólares (US$)',
    ];

    /**
     * Obtiene la moneda actualmente activa para visualización en la sesión.
     */
    public static function getActiveCurrency(): string
    {
        $currency = Session::get('active_currency');
        if ($currency && in_array($currency, [self::CURRENCY_GTQ, self::CURRENCY_USD])) {
            return $currency;
        }

        return static::getTenantDefaultCurrency();
    }

    /**
     * Fija la moneda activa en la sesión.
     */
    public static function setActiveCurrency(string $currency): void
    {
        if (in_array($currency, [self::CURRENCY_GTQ, self::CURRENCY_USD])) {
            Session::put('active_currency', $currency);
        }
    }

    /**
     * Alterna la moneda activa entre GTQ y USD.
     */
    public static function toggleCurrency(): string
    {
        $current = static::getActiveCurrency();
        $next = $current === self::CURRENCY_GTQ ? self::CURRENCY_USD : self::CURRENCY_GTQ;
        static::setActiveCurrency($next);
        return $next;
    }

    /**
     * Moneda predeterminada del taller actual.
     */
    public static function getTenantDefaultCurrency(): string
    {
        $tenant = static::getCurrentTenant();
        return $tenant?->default_currency ?? self::CURRENCY_GTQ;
    }

    /**
     * Tasa de cambio configurada en el taller: 1 USD = X GTQ (por defecto 7.80).
     */
    public static function getExchangeRate(?Tenant $tenant = null): float
    {
        $targetTenant = $tenant ?: static::getCurrentTenant();
        $rate = (float) ($targetTenant?->exchange_rate ?? 7.80);
        return $rate > 0 ? $rate : 7.80;
    }

    /**
     * Obtiene el símbolo de la moneda.
     */
    public static function getSymbol(?string $currency = null): string
    {
        $curr = $currency ?: static::getActiveCurrency();
        return static::SYMBOLS[$curr] ?? 'Q';
    }

    /**
     * Convierte un monto base en Quetzales (GTQ) a la moneda destino.
     */
    public static function convertFromGtq(float|int|null $amountInGtq, ?string $targetCurrency = null, ?float $customRate = null): float
    {
        $amount = (float) ($amountInGtq ?? 0);
        $target = $targetCurrency ?: static::getActiveCurrency();
        $rate = $customRate ?: static::getExchangeRate();

        if ($target === self::CURRENCY_USD) {
            return $rate > 0 ? round($amount / $rate, 2) : 0.0;
        }

        return round($amount, 2);
    }

    /**
     * Convierte un monto en Dólares (USD) a Quetzales (GTQ).
     */
    public static function convertToGtq(float|int|null $amountInUsd, ?float $customRate = null): float
    {
        $rate = $customRate ?: static::getExchangeRate();
        return round(((float) $amountInUsd) * $rate, 2);
    }

    /**
     * Formatea un monto en base GTQ según la moneda activa.
     * Ejemplo: format(780) -> "Q 780.00" o "US$ 100.00"
     */
    public static function format(float|int|null $amountInGtq, ?string $currency = null, bool $withSymbol = true): string
    {
        $curr = $currency ?: static::getActiveCurrency();
        $converted = static::convertFromGtq($amountInGtq, $curr);
        $formatted = number_format($converted, 2);

        if (!$withSymbol) {
            return $formatted;
        }

        $symbol = static::getSymbol($curr);
        return $symbol . ($curr === self::CURRENCY_USD ? ' ' : '') . $formatted;
    }

    /**
     * Formatea un monto mostrando la moneda activa y el equivalente secundario.
     * Ejemplo: "Q 780.00 (US$ 100.00)" o "US$ 100.00 (Q 780.00)"
     */
    public static function formatDual(float|int|null $amountInGtq): string
    {
        $curr = static::getActiveCurrency();
        $base = (float) ($amountInGtq ?? 0);
        $rate = static::getExchangeRate();

        if ($curr === self::CURRENCY_USD) {
            $usd = $rate > 0 ? $base / $rate : 0;
            return 'US$ ' . number_format($usd, 2) . ' (Q ' . number_format($base, 2) . ')';
        }

        $usd = $rate > 0 ? $base / $rate : 0;
        return 'Q ' . number_format($base, 2) . ' (US$ ' . number_format($usd, 2) . ')';
    }

    /**
     * Retorna datos para tarjeta visual:
     * ['primary' => 'Q 780.00', 'secondary' => '≈ US$ 100.00', 'symbol' => 'Q', 'amount' => 780.00, 'usd' => 100.00, 'gtq' => 780.00]
     */
    public static function breakdown(float|int|null $amountInGtq): array
    {
        $curr = static::getActiveCurrency();
        $base = (float) ($amountInGtq ?? 0);
        $rate = static::getExchangeRate();
        $usd = $rate > 0 ? round($base / $rate, 2) : 0.0;

        if ($curr === self::CURRENCY_USD) {
            return [
                'currency' => self::CURRENCY_USD,
                'symbol' => 'US$',
                'primary' => 'US$ ' . number_format($usd, 2),
                'secondary' => '≈ Q ' . number_format($base, 2),
                'amount' => $usd,
                'gtq' => $base,
                'usd' => $usd,
                'rate' => $rate,
            ];
        }

        return [
            'currency' => self::CURRENCY_GTQ,
            'symbol' => 'Q',
            'primary' => 'Q ' . number_format($base, 2),
            'secondary' => '≈ US$ ' . number_format($usd, 2),
            'amount' => $base,
            'gtq' => $base,
            'usd' => $usd,
            'rate' => $rate,
        ];
    }

    /**
     * Retorna el tenant contextual actual.
     */
    public static function getCurrentTenant(): ?Tenant
    {
        $user = Auth::user();
        if ($user && $user->tenant) {
            return $user->tenant;
        }

        if (Session::has('tenant_id')) {
            return Tenant::find(Session::get('tenant_id'));
        }

        return Tenant::where('is_active', true)->first();
    }
}
