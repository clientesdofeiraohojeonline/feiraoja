<?php
// Configurações
$API_TOKEN = '4d90545d3421dcb3c63a4361f931cbf18c3d0747951590c1df2d2e65b260986f';
$API_BASE = 'https://api.bluenext2.online/api/v1/consult/';

// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Responder a requisições OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Obter o CPF da URL
$cpf = isset($_GET['cpf']) ? preg_replace('/[^\d]/', '', $_GET['cpf']) : null;

if (!$cpf) {
    http_response_code(400);
    echo json_encode(['error' => 'CPF não fornecido']);
    exit;
}

// Fazer requisição para a API externa
$url = $API_BASE . $cpf;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $API_TOKEN,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao consultar API: ' . $curl_error]);
    exit;
}

http_response_code($http_code);
echo $response;
?>
