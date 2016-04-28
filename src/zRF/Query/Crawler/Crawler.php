<?php
namespace zRF\Query\Crawler;

use Symfony\Component\DomCrawler\Crawler as BaseCrawler;

/**
*
*/
class Crawler extends BaseCrawler
{
    /**
     * HTML Selectors
     * @var [type]
     */
    private $selectors = [
        'numero_inscricao'  => 'body > table:nth-child(3) > tr > td > table:nth-child(3) > tr > td:nth-child(1) > font:nth-child(3) > b:nth-child(1)',
        'classificacao'     => 'body > table:nth-child(3) > tr > td > table:nth-child(3) > tr > td:nth-child(1) > font:nth-child(3) > b:nth-child(3)',
        'data_abertura'     => 'body > table:nth-child(3) > tr > td > table:nth-child(3) > tr > td:nth-child(3) > font:nth-child(3) > b',
        'nome_empresarial'  => 'body > table:nth-child(3) > tr > td > table:nth-child(5) > tr > td > font:nth-child(3) > b',
        'nome_fantasia'     => 'body > table:nth-child(3) > tr > td > table:nth-child(7) > tr > td > font:nth-child(3) > b',
        'cnae_principal'    => 'body > table:nth-child(3) > tr > td > table:nth-child(9) > tr > td > font:nth-child(3) > b',
        'cnae_secundarios'  => ['body > table:nth-child(3) > tr > td > table:nth-child(11) > tr > td' => 'td > font > b'],
        'natureza_juridica' => 'body > table:nth-child(3) > tr > td > table:nth-child(13) > tr > td > font:nth-child(3) > b',
        'endereco'          => 'body > table:nth-child(3) > tr > td > table:nth-child(15) > tr > td:nth-child(1) > font:nth-child(3) > b',
        'numero'            => 'body > table:nth-child(3) > tr > td > table:nth-child(15) > tr > td:nth-child(3) > font:nth-child(3) > b',
        'complemento'       => 'body > table:nth-child(3) > tr > td > table:nth-child(15) > tr > td:nth-child(5) > font:nth-child(3) > b',
        'cep'               => 'body > table:nth-child(3) > tr > td > table:nth-child(17) > tr > td:nth-child(1) > font:nth-child(3) > b',
        'distrito'          => 'body > table:nth-child(3) > tr > td > table:nth-child(17) > tr > td:nth-child(3) > font:nth-child(3) > b',
        'municipio'         => 'body > table:nth-child(3) > tr > td > table:nth-child(17) > tr > td:nth-child(5) > font:nth-child(3) > b',
        'uf'                => 'body > table:nth-child(3) > tr > td > table:nth-child(17) > tr > td:nth-child(7) > font:nth-child(3) > b',
        'email'             => 'body > table:nth-child(3) > tr > td > table:nth-child(19) > tr > td:nth-child(1) > font:nth-child(3) > b',
        'telefone'          => 'body > table:nth-child(3) > tr > td > table:nth-child(19) > tr > td:nth-child(3) > font:nth-child(3) > b',
        'efr'               => 'body > table:nth-child(3) > tr > td > table:nth-child(21) > tr > td > font:nth-child(3) > b',
        'situacao'          => 'body > table:nth-child(3) > tr > td > table:nth-child(23) > tr > td:nth-child(1) > font:nth-child(3) > b',
        'data_situacao'     => 'body > table:nth-child(3) > tr > td > table:nth-child(23) > tr > td:nth-child(3) > font:nth-child(3) > b',
        'motivo_situacao'   => 'body > table:nth-child(3) > tr > td > table:nth-child(25) > tr > td:nth-child(3) > font:nth-child(3) > b',
        'situacao_especial' => 'body > table:nth-child(3) > tr > td > table:nth-child(27) > tr > td:nth-child(1) > font:nth-child(3) > b',
        'data_situacao_especial' => 'body > table:nth-child(3) > tr > td > table:nth-child(27) > tr > td:nth-child(3) > font:nth-child(3) > b'

    ];

    /**
     * Extrai informações do HTML através do DOM
     *
     * @return array
     */
    public function scraping()
    {
        $scrapped = array();

        foreach ($this->selectors as $name => $selector) {

            if(is_string($selector)){
                $node = $this->scrap($selector);

                if($node->count()){
                    $scrapped[$name] = $this->clearString($node->text());
                }
            }elseif(is_array($selector)){
                foreach ($selector as $selector => $repeat) {
                    $node = $this->scrap($selector);

                    foreach ($node->filter($repeat) as $loop)
                    {
                        $scrapped[$name][] = $this->clearString($loop->nodeValue);
                    }
                }
            }
        }

        return $scrapped;
    }

    /**
     * Limpa o valor repassado
     * @param  string $string
     * @return string
     */
    public function clearString($string)
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    /**
     * Filtra selector no crawler
     */
    public function scrap($selector)
    {
        $node = $this->filter($selector);
        return $node;
    }
}