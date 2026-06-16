<?php
// Link da sua fonte (Central da TV)
$lista_origem = "http://xccbuene.sbs"; 

// Tenta pegar o conteúdo da lista
$conteudo = @file_get_contents($lista_origem);

// Se não conseguir acessar, exibe um erro simples
if ($conteudo === false) {
    die("Erro: Nao foi possivel acessar a lista de canais. Verifique o link de origem.");
}

// Configura o cabeçalho para o formato M3U (o que os players esperam)
header('Content-Type: application/x-mpegURL');
header('Content-Disposition: inline; filename="playlist.m3u"');

// Entrega a lista
echo $conteudo;
?>