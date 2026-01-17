<?php
function addToCart($product_id, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if (isset($item['id']) && $item['id'] == $product_id) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    // If not found, add as new item
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'quantity' => $quantity,
            'added_at' => date('Y-m-d H:i:s')
        ];
    }
}

function calculateCartCount() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        return 0;
    }
    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['quantity'])) {
            $count += $item['quantity'];
        }
    }
    return $count;
}

function getCartItems() {
    return $_SESSION['cart'] ?? [];
}

function removeFromCart($index) {
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
    }
}

function clearCart() {
    $_SESSION['cart'] = [];
}

function updateCartQuantity($index, $quantity) {
    if (isset($_SESSION['cart'][$index])) {
        if ($quantity <= 0) {
            removeFromCart($index);
        } else {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        }
    }
}
?>