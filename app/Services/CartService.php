<?php

namespace App\Services;

class CartService
{
    /**
     * Add a product to the cart. Merges quantity if already exists.
     */
    public function add(array $cart, $cartItemId, int $qty, array $productData): array
    {
        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $qty;
        } else {
            $cart[$cartItemId] = [
                'id'         => $cartItemId,
                'product_id' => $productData['product_id'] ?? $cartItemId,
                'name'       => $productData['name'],
                'price'      => $productData['price'],
                'img'        => $productData['img'] ?? null,
                'quantity'   => $qty,
                'storage'    => $productData['storage'] ?? null,
                'color'      => $productData['color'] ?? null,
            ];
        }
        return $cart;
    }

    /**
     * Update quantity of a product. Removes if qty <= 0.
     */
    public function update(array $cart, $cartItemId, int $qty): array
    {
        if ($qty <= 0) {
            unset($cart[$cartItemId]);
        } else {
            if (isset($cart[$cartItemId])) {
                $cart[$cartItemId]['quantity'] = $qty;
            }
        }
        return $cart;
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(array $cart, $cartItemId): array
    {
        unset($cart[$cartItemId]);
        return $cart;
    }

    /**
     * Calculate total price of all items in cart.
     */
    public function total(array $cart): float
    {
        return array_reduce($cart, function (float $carry, array $item): float {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0.0);
    }
}
