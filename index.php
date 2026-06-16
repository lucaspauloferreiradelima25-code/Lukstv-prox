<?php
/**
 * PROXY PARA IPTV - LUKA-TV
 * Este arquivo mascara o link real do servidor da Central.
 */

// 1. Configuração do Servidor Real
$servidorBase = "http://xcbuny.sbs";

// 2. Captura os dados enviados pelo player do cliente
// Se o cliente não enviar usuário/senha, o script para aqui
$username = $_GET['username'] ?? '';
$password = $_GET['password'] ?? '';
$type = $_GET['type'] ?? 'm3u_plus';

// 3. Verifica se os dados foram enviados
if (empty($username) || empty($password)) {
    die("Erro: Dados de acesso não fornecidos.");
}

// 4. Monta a URL completa para buscar no servidor da Central
$urlFinal = $servidorBase . "/get.php?username=" . $username . "&password=" . $password . "&type=" . $type;

// 5. Inicia a requisição (O "segredo" que faz rodar liso)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlFinal);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Pega o conteúdo como texto
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Segue redirecionamentos
curl_setopt($ch, CURLOPT_TIMEOUT, 30);          // Tempo limite de 30 segundos
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'); // Simula um navegador comum

// Executa e fecha a conexão
$conteudo = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURL_HTTP_CODE);
curl_close($ch);

// 6. Entrega o conteúdo para o player do cliente
if ($httpCode == 200) {
    header("Content-Type: application/x-mpegURL");
    header("Content-Disposition: attachment; filename=lista.m3u");
    echo $conteudo;
} else {
    // Caso dê erro no servidor da Central
    header("HTTP/1.0 500 Internal Server Error");
    echo "Erro ao conectar com o servidor central.";
}
?>