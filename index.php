<?php

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Autoload (DEVE vir antes do seu arquivo)
require __DIR__ . '/vendor/autoload.php';

// Importa o arquivo com a função
require __DIR__ . '/rastrear.php';

// Identifica rota atual
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// -------------------------------
// 🚀 ROTA /rastrear
// -------------------------------
if ($path === '/rastrear') {

    if (!isset($_GET['codigo']) || empty($_GET['codigo'])) {
        echo json_encode([
            'erro' => true,
            'mensagem' => 'Você deve enviar ?codigo=SEU_CODIGO'
        ]);
        exit;
    }

    // Permite múltiplos códigos separados por vírgula
    $codigos = explode(',', $_GET['codigo']);

    $resultado = rastrearCodigos($codigos);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// -------------------------------
// ❌ Rota não encontrada
// -------------------------------
http_response_code(404);
echo json_encode([
    'erro' => true,
    'mensagem' => 'Rota não encontrada'
]);
