<?php
header('Content-Type: application/json');
require_once dirname(__FILE__) . 'D:\Mobile_SI6P\midtrans\vendor'; // Sesuaikan path SDK Midtrans
require_once 'env.php';

// Ambil data input dari Flutter
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['order_id']) || !isset($input['gross_amount'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit();
}

\Midtrans\Config::$serverKey = getServerKey();
\Midtrans\Config::$isProduction = IS_PRODUCTION;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

$params = [
    'transaction_details' => [
        'order_id' => $input['order_id'],
        'gross_amount' => (int)$input['gross_amount'],
    ]
];

try {
    // Ambil Snap Token dari Midtrans
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    $snapUrl = \Midtrans\Snap::getSnapUrl($params);
    echo json_encode(['status' => 'success', 'token' => $snapToken, 'redirect_url' => $snapUrl]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
