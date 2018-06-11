<?php

include_once __DIR__ . "/BniEnc.php";


// FROM BNI
$client_id = 'XXX';
$secret_key = 'xxxx';


// URL utk simulasi pembayaran: http://dev.bni-ecollection.com/


$data = file_get_contents('php://input');

$data_json = json_decode($data, true);

if (!$data_json) {
	// handling orang iseng
	echo '{"status":"999","message":"jangan iseng :D"}';
}
else {
	if ($data_json['client_id'] === $client_id) {
		$data_asli = BniEnc::decrypt(
			$data_json['data'],
			$client_id,
			$secret_key
		);

		if (!$data_asli) {
			// handling jika waktu server salah/tdk sesuai atau secret key salah
			echo '{"status":"999","message":"waktu server tidak sesuai NTP atau secret key salah."}';
		}
		else {
			// insert data asli ke db
			/* $data_asli = array(
				'trx_id' => '', // silakan gunakan parameter berikut sebagai acuan nomor tagihan
				'virtual_account' => '',
				'customer_name' => '',
				'trx_amount' => '',
				'payment_amount' => '',
				'cumulative_payment_amount' => '',
				'payment_ntb' => '',
				'datetime_payment' => '',
				'datetime_payment_iso8601' => '',
			); */
			echo '{"status":"000"}';
			exit;
		}
	}
}



