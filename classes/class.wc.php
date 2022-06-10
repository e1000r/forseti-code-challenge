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
        
        echo '<pre>';
        print_r($this->dados);
        echo '</pre>';
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
            $datas[] = $dn;
        }

        foreach($hora as $hor)
        {
            $hn = preg_replace( "/\r|\n/", "", $hor );
            $hn = str_replace(' ', '', $hn);
            $horas[] = $hn;
        }

        $dados = array(
            'titulo' => $title,
            'link' => $link,
            'data' => $datas,
            'hora'  => $horas
        );
        
        array_push($this->dados, $dados);
    }
}
?>