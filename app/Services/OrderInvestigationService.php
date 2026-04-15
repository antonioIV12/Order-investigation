<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OrderInvestigationService
{
    public function fetchCartpandaData(string $slug, string $token, string $orderNumber): array
    {
        $response = Http::withToken($token)->get("https://accounts.cartpanda.com/api/v3/{$slug}/orders/{$orderNumber}");

        if (!$response->successful()) {
            return ['Cartpanda Result' => 'Not Found'];
        }

        $order = $response->json()['order'] ?? [];
        $addr = $order['billing_address'] ?? [];

        return [
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
    }

    public function fetchCartroverData(string $user, string $key, string $cartpandaId): array
    {
        $response = Http::withBasicAuth($user, $key)
            ->get("https://api.cartrover.com/v1/merchant/orders/{$cartpandaId}");

        if (!$response->successful()) {
            return ['Cartrover Result' => 'Not Found'];
        }

        return ['Cartrover Result' => 'Found'];
    }
}
