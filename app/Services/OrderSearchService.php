<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

class OrderSearchService
{

    public const decay_rate = 60;
    public const request_per_min = 60;

    public function findVerifiedOrderId(string $slug, string $token, string $email, string $targetName): ?int
    {
        return RateLimiter::attempt("cp-search:{$slug}", self::request_per_min, function () use ($slug, $token, $email, $targetName) {
            $response = Http::withToken($token)->get("https://accounts.cartpanda.com/api/v3/{$slug}/orders", [
                'email' => $email
            ]);

            if (!$response->successful()) return null;

            $orders = $response->json()['orders'] ?? [];
            if (empty($orders)) return null;

            // 1. Try to find an exact name match
            foreach ($orders as $order) {
                $foundName = $order['billing_address']['name'] ?? $order['name'] ?? '';
                if (strcasecmp(trim($foundName), trim($targetName)) === 0) {
                    return (int) $order['id'];
                }
            }

            // 2. Fallback: Take the most recent order for this email (Python logic)
            usort($orders, function ($a, $b) {
                return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
            });

            return (int) $orders[0]['id'];
        }, self::decay_rate);
    }

    public function getFormattedOrderData(string $slug, string $token, int $orderId): array
    {
        return RateLimiter::attempt("cp-data:{$slug}", self::request_per_min, function () use ($slug, $token, $orderId) {
            $response = Http::withToken($token)->get("https://accounts.cartpanda.com/api/v3/{$slug}/orders/{$orderId}");
            if (!$response->successful()) return ['Cartpanda Result' => 'Not Found'];

            $order = $response->json()['order'] ?? [];
            $addr = $order['billing_address'] ?? [];

            return [
                'Order Number'       => $order['id'] ?? 'N/A', // Using ID as Order Number for this flow
                'Cartpanda ID'       => $order['id'] ?? 'N/A',
                'Payment Status'     => $order['payment_status'] ?? 'N/A',
                'Fulfillment Status' => $order['fulfillment_status'] ?? 'N/A',
                'Status ID'          => $order['status_id'] ?? 'N/A',
                'CP SKU'             => collect($order['line_items'] ?? [])->pluck('sku')->implode(', '),
                'Test Order'         => ($order['test'] ?? false) ? 'Yes' : 'No',
                'Cartpanda Result'   => 'Found',
                'address2'           => $addr['address2'] ?? 'N/A',
                'address1'           => $addr['address1'] ?? 'N/A',
                'city'               => $addr['city'] ?? 'N/A',
                'country'            => $addr['country'] ?? 'N/A',
                'first_name'         => $addr['first_name'] ?? 'N/A',
                'last_name'          => $addr['last_name'] ?? 'N/A',
                'phone'              => $addr['phone'] ?? 'N/A',
                'province'           => $addr['province'] ?? 'N/A',
                'zip'                => $addr['zip'] ?? 'N/A',
                'name'               => $addr['name'] ?? 'N/A',
                'province_code'      => $addr['province_code'] ?? 'N/A',
                'country_code'       => $addr['country_code'] ?? 'N/A',
            ];
        }, self::decay_rate) ?? ['Cartpanda Result' => 'Rate Limit Hit'];
    }
}
