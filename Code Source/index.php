<?php
// WooCommerce API credentials
$consumerKey = 'ck_4ae5e87c7118135896248a497142f05c640a2ef0';
$consumerSecret = 'cs_d736b30aea7eb50edb5aaf3e5a79786d44f8d81d';
$storeUrl = 'https://sajiriyadh.com/wp-json/wc/v3/products';

// Increase script execution time
set_time_limit(300);

// Variables for pagination
$page = 1;
$perPage = 10; // Fetch 10 products per page

do {
    // Initialize cURL
    $curl = curl_init();

    // Add pagination parameters to the URL
    $urlWithPagination = $storeUrl . "?page=$page&per_page=$perPage";

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $urlWithPagination,
        CURLOPT_USERPWD => $consumerKey . ':' . $consumerSecret,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        echo "cURL Error: " . curl_error($curl);
        break; // Exit loop if there's an error
    }

    // Decode the response
    $pageProducts = json_decode($response, true);

    // Close cURL
    curl_close($curl);

    // Check if there are products on this page
    if (!empty($pageProducts)) {
        // Display products from the current page
        foreach ($pageProducts as $product) {
            echo "Name: " . $product['name'] . "<br>";
            echo "Price: " . $product['price'] . "<br>";
            echo "SKU: " . $product['sku'] . "<br>";
            if (!empty($product['images'])) {
                echo "<img src='" . $product['images'][0]['src'] . "' alt='Product Image' style='width:100px;'><br>";
            }
            echo "<hr>";
        }
        $page++; // Move to the next page
    } else {
        break; // No more products
    }
} while (!empty($pageProducts));
?>
