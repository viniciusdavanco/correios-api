<?php
phpinfo();
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Para preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Importa a função do seu arquivo rastrear.php
require __DIR__ . '/rastrear.php';

// Obtém a rota solicitada
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// --------------------
// 🚀 ROTA: /rastrear
// Exemplo GET:
// http://localhost:8080/rastrear?codigo=AN349261424BR
// http://localhost:8080/rastrear?codigo=COD1,COD2,COD3
// --------------------
if ($path === '/rastrear') {

    if (!isset($_GET['codigo'])) {
        echo json_encode([
            'erro' => true,
            'mensagem' => 'Você deve enviar ?codigo=SEU_CODIGO'
        ]);
        exit;
    }

    // Permitir múltiplos códigos separados por vírgula
    $codigos = explode(',', $_GET['codigo']);

    $resultado = rastrearCodigos($codigos);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// --------------------
// ❌ Rota não encontrada
// --------------------
http_response_code(404);
echo json_encode([
    'erro' => true,
    'mensagem' => 'Rota não encontrada'
]);
