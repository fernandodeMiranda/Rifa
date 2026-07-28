<?php

namespace App\Helpers;

/**
 * Upload de comprovantes de pagamento (imagem ou PDF).
 * Salva em public/uploads/comprovantes com nome único.
 */
final class Upload
{
    public static function salvarComprovante(array $arquivo): string
    {
        $config = require __DIR__ . '/../Config/config.php';

        if (!in_array($arquivo['type'] ?? '', $config['upload_mimes_aceitos'], true)) {
            throw new \InvalidArgumentException('Tipo de arquivo não permitido. Envie imagem (JPG/PNG) ou PDF.');
        }

        $tamanhoMaximo = $config['upload_max_size_mb'] * 1024 * 1024;
        if (($arquivo['size'] ?? 0) > $tamanhoMaximo) {
            throw new \InvalidArgumentException("Arquivo excede o tamanho máximo de {$config['upload_max_size_mb']}MB.");
        }

        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid('comprovante_', true) . '.' . $extensao;
        $destino = rtrim($config['upload_dir'], '/') . '/' . $nomeArquivo;

        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            throw new \RuntimeException('Falha ao salvar o comprovante enviado.');
        }

        return $nomeArquivo;
    }
}
