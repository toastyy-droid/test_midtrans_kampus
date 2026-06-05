<?php
echo "Webhook OK";
require_once dirname(__FILE__) . '/vendor/autoload.php';
require_once 'env.php';

// Pastikan Anda sudah menginstall Appwrite PHP SDK (composer require appwrite/appwrite)
use Appwrite\Client;
use Appwrite\Services\Databases;

$init = new \Midtrans\Notification();

$transaction = $init->transaction_status;
$type = $init->payment_type;
$order_id = $init->order_id;
$status_code = $init->status_code;

// Tentukan status internal toko online Anda
$status_toko = 'pending';

if ($transaction == 'settlement') {
    $status_toko = 'terbayar';
} else if ($transaction == 'pending') {
    $status_toko = 'pending';
} else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
    $status_toko = 'gagal';
}

// Hubungkan ke Appwrite untuk update status order
$client = new Client();
$client
    ->setEndpoint('https://cloud.appwrite.io/v1') // Ganti jika pakai self-hosted
    ->setProject('69fd502b001cc8a7cd06')
    ->setKey('standard_d7de98ac77b7c77b2be3d89e52526e7c435f8daacdea99876d6c37580f10084c84139612c4a9d115a976fb6d553ae8bd0ccc93125cab93f52c91631724ef6e7ae068a761895035d3ec39d9634e9a49146203699dab46cc3c503a3de04fe34ab40b620423ac2005ff8ff73cb718e390e9862094fbf24b6879d0a22c545fdf0240'); // Butuh API Key dengan akses 'documents.write'

$databases = new Databases($client);

try {
    // Update dokumen order di Appwrite berdasarkan order_id (sebagai Document ID)
    $databases->updateDocument(
        'DATABASE_ID', 
        'COLLECTION_ID_ORDERS', 
        $order_id, // Menggunakan order_id dari midtrans sebagai ID Dokumen
        [
            'status' => $status_toko // Pastikan attribute 'status' ada di Appwrite
        ]
    );
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    file_put_contents('error_log.txt', $e->getMessage(), FILE_APPEND);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
