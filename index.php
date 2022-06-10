<?php
// https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias
// Extrair as informações necessárias (manchete, data/hora e link dos detalhes)
// Buscar notícias até a página 5
// Salvar esses dados estruturados em um banco de dados ou num arquivo csv
// Garantir que as notícias não se dupliquem no registro final

require_once 'classes/class.wc.php';
$wc = new WebCrawler;
?>