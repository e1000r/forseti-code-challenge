<?php
class WebCrawler
{
    function __construct()
    {
        $this->dados = array();
        
        $i = 0;
        while($i <= 120)
        {
            $page = file_get_contents('https://www.gov.br/compras/pt-br/acesso-a-informacao/noticias?b_start:int='.$i);
            // mais páginas a cada 30
            // ?b_start:int=30
            $this->GetContent($page);

            $i = $i + 30;
        }
        
        // Verifica se está pegando os dados de forma correta
        // echo '<pre>';
        // print_r($this->dados);
        // echo '</pre>';

        // Verifica se está juntando os dados de forma correta
        foreach ($this->dados as $dados)
        {
            foreach($dados as $dado)
            {
                $linha[] = array(
                    $dado[0],
                    $dado[1],
                    $dado[2],
                    $dado[3]
                );
            }
        }
        // echo '<pre>';
        // print_r($linha);
        // echo '<pre>';

        // Grava o arquivo CSV
        $this->GravaCSV();
        // Baixa o arquivo CSV
        $this->DownloadCSV();
    }

    function GetContent($page)
    {
        $d = preg_match_all('/<h2 class="tileHeadline"[^>]*>(.*?)<\/h2>/ims', $page, $match) ? $match[1] : null;
        $title = preg_match_all('/<a class="summary url"[^>]*>(.*?)<\/a>/ims', $page, $match) ? $match[1] : null;
        $data = preg_match_all('/<i class="icon-day"[^>]*>(.*?)<\/span>/ims', $page, $match) ? $match[1] : null;
        $hora = preg_match_all('/<i class="icon-hour"[^>]*>(.*?)<\/span>/ims', $page, $match) ? $match[1] : null;
        
        foreach($d as $dados)
        {
            $l = $dados;
            $start = stripos($l, 'href="');
            $end = stripos($l, 'title="', $offset = $start);
            $length = $end - $start;
            $t = substr($l, $start, $length);
            $t = str_replace('href=', '', $t);
            $t = str_replace('"', '', $t);
            $link[] = $t;
        }
        
        foreach($data as $dat)
        {
            $dn = preg_replace( "/\r|\n/", "", $dat );
            $dn = str_replace(' ', '', $dn);
            $dn = str_replace('</i>', '', $dn);
            $datas[] = $dn;
        }

        foreach($hora as $hor)
        {
            $hn = preg_replace( "/\r|\n/", "", $hor );
            $hn = str_replace(' ', '', $hn);
            $hn = str_replace('</i>', '', $hn);
            $horas[] = $hn;
        }

        foreach($title as $id => $t)
        {
            $dsa[$id] = array(
                $t,
                $link[$id],
                $datas[$id],
                $horas[$id]
            );
        }
        
        array_push($this->dados, $dsa);
    }

    function GravaCSV()
    {
        // Exclui os arquivos .csv anteriores
		array_map('unlink', glob("*.csv"));

        // Gera o arquivo CSV com a data atual
        $arquivo = fopen('forseti-'.date('d-m-Y').'.csv', 'w');

        // Gera o cabeçalho
        $headers = ['Título', 'Link', 'Data', 'Hora'];
        fputcsv($arquivo, $headers);

        // Escreve os dados no arquivo
        foreach ($this->dados as $dados)
        {
            foreach($dados as $dado)
            {
                $linha = array(
                    $dado[0],
                    $dado[1],
                    $dado[2],
                    $dado[3]
                );
                // Grava a linha com os dados no arquivo CSV
                fputcsv($arquivo, $linha);
            }
        }

        // Fecha o arquivo
        fclose($arquivo);
    }

    function DownloadCSV()
    {
        $arquivo = 'forseti-'.date('d-m-Y').'.csv';
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($arquivo));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($arquivo));
        readfile($arquivo);
    }
}
?>