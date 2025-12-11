<?php

require __DIR__ . '/vendor/autoload.php';

use Sdkcorreios\Config\Services;
use Sdkcorreios\Methods\Tracking;

/**
 * Função que rastreia um ou mais códigos e retorna JSON (array puro)
 */
function rastrearCodigos(array $codigos)
{
    // Define o provedor
    Services::setServiceTracking('0003');

    $tracking = new Tracking();

    foreach ($codigos as $codigo) {
        $tracking->setCode($codigo);
    }

    $resultado = $tracking->get();

    if (Services::$success) {
        return $resultado;
    }

    return [
        'erro' => true,
        'mensagem' => 'Erro ao rastrear o(s) objeto(s). Verifique os códigos ou o serviço.',
    ];
}

/**
 * --- MODO CLI ---
 * Se o arquivo for chamado pelo terminal, executa como antes:
 * php rastrear.php XYZ123BR OUTRO123BR
 */
if (php_sapi_name() === 'cli') {

    global $argc, $argv;

    if ($argc < 2) {
        echo "Uso: php rastrear.php CODIGO1 [CODIGO2] [...]\n";
        exit(1);
    }

    // Remove o nome do arquivo da lista
    $codigos = array_slice($argv, 1);

    echo "Rastreando os seguintes códigos:\n";
    foreach ($codigos as $codigo) {
        echo "- {$codigo}\n";
    }

    echo "\nAguarde...\n\n";

    $resultado = rastrearCodigos($codigos);

    echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";

    exit;
}

if (php_sapi_name() !== 'cli') {
    // 1. Verifica se o código foi passado via GET
    if (isset($_GET['codigo']) && !empty($_GET['codigo'])) {
        $codigos = explode(',', $_GET['codigo']); // Permite múltiplos códigos separados por vírgula

        // 2. Chama a função de rastreamento
        $resultado = rastrearCodigos($codigos);

        // 3. Imprime o JSON e define o cabeçalho correto
        header('Content-Type: application/json');
        echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {
        // Se nenhum código for fornecido, retorna um erro JSON
        header('Content-Type: application/json');
        echo json_encode([
            'erro' => true,
            'mensagem' => 'Parâmetro "codigo" não fornecido. Use: /rastrear.php?codigo=SEU_CODIGO'
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    exit;
}
