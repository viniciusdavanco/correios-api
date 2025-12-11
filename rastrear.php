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
