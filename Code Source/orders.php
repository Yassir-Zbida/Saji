<?php
// WooCommerce API credentials
$consumerKey = 'ck_4ae5e87c7118135896248a497142f05c640a2ef0';
$consumerSecret = 'cs_d736b30aea7eb50edb5aaf3e5a79786d44f8d81d';
$storeUrl = 'https://sajiriyadh.com/wp-json/wc/v3/orders';

set_time_limit(300);

$page = 1;
$perPage = 10; 

do {
    $curl = curl_init();

    $urlWithPagination = $storeUrl . "?page=$page&per_page=$perPage";

    curl_setopt_array($curl, [
        CURLOPT_URL => $urlWithPagination,
        CURLOPT_USERPWD => $consumerKey . ':' . $consumerSecret,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "cURL Error: " . curl_error($curl);
        break; // Exit loop if there's an error
    }

    $pageOrders = json_decode($response, true);

    curl_close($curl);

    if (!empty($pageOrders)) {
        foreach ($pageOrders as $order) {
            echo "<strong>Order ID:</strong> " . $order['id'] . "<br>";
            echo "<strong>Date:</strong> " . $order['date_created'] . "<br>";
            echo "<strong>Status:</strong> " . $order['status'] . "<br>";
            echo "<strong>Total:</strong> " . $order['total'] . " " . $order['currency'] . "<br>";

            if (!empty($order['billing'])) {
                echo "<strong>Customer:</strong> " . $order['billing']['first_name'] . " " . $order['billing']['last_name'] . "<br>";
                echo "<strong>Email:</strong> " . $order['billing']['email'] . "<br>";
                echo "<strong>Phone:</strong> " . $order['billing']['phone'] . "<br>";
            }

            if (!empty($order['line_items'])) {
                echo "<strong>Items:</strong><br>";
                foreach ($order['line_items'] as $item) {
                    echo "- " . $item['name'] . " (Qty: " . $item['quantity'] . ") - " . $item['total'] . " " . $order['currency'] . "<br>";
                }
            }
            echo "<hr>";
        }
        $page++;
    } else {
        break; 
    }
} while (!empty($pageOrders));
?>
