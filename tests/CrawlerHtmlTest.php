<?php

use zRF\Query\Crawler\Crawler;

class CrawlerHtmlTest extends PHPUnit_Framework_TestCase
{

	public function testFileExist()
    {
    	$file = dirname(__FILE__) . '/test.html';
    	$this->assertFileExists($file);
        $this->assertStringNotEqualsFile($file, '');

        return file_get_contents($file);
    }

    /**
     * @depends testFileExist
     */
    public function testCrawler($fileContents)
    {
    	$crawler = new Crawler($fileContents);

    	$scrapped = $crawler->scraping();

    	$this->assertTrue(
	    		(is_array($scrapped) && count($scrapped) > 0), 
	    		'Type of scraped crawler not is valid array'
	    	);

		return json_encode($scrapped);
    }

    /**
     * @depends testCrawler
     */
    public function testJsonEqualsValues($json)
    {
    	$jsonComparation = '{"numero_inscricao":"14.050.180\/0001-20","classificacao":"MATRIZ","data_abertura":"28\/07\/2011","nome_empresarial":"PALO ALTO ELETRONICOS LTDA - ME","nome_fantasia":"********","cnae_principal":"46.49-4-02 - Com\u00c3\u00a9rcio atacadista de aparelhos eletr\u00c3\u00b4nicos de uso pessoal e dom\u00c3\u00a9stico","cnae_secundarios":["46.51-6-01 - Com\u00c3\u00a9rcio atacadista de equipamentos de inform\u00c3\u00a1tica","46.52-4-00 - Com\u00c3\u00a9rcio atacadista de componentes eletr\u00c3\u00b4nicos e equipamentos de telefonia e comunica\u00c3\u00a7\u00c3\u00a3o","46.49-4-01 - Com\u00c3\u00a9rcio atacadista de equipamentos el\u00c3\u00a9tricos de uso pessoal e dom\u00c3\u00a9stico","47.53-9-00 - Com\u00c3\u00a9rcio varejista especializado de eletrodom\u00c3\u00a9sticos e equipamentos de \u00c3\u00a1udio e v\u00c3\u00addeo","43.21-5-00 - Instala\u00c3\u00a7\u00c3\u00a3o e manuten\u00c3\u00a7\u00c3\u00a3o el\u00c3\u00a9trica","95.21-5-00 - Repara\u00c3\u00a7\u00c3\u00a3o e manuten\u00c3\u00a7\u00c3\u00a3o de equipamentos eletroeletr\u00c3\u00b4nicos de uso pessoal e dom\u00c3\u00a9stico","80.20-0-01 - Atividades de monitoramento de sistemas de seguran\u00c3\u00a7a eletr\u00c3\u00b4nico","74.90-1-04 - Atividades de intermedia\u00c3\u00a7\u00c3\u00a3o e agenciamento de servi\u00c3\u00a7os e neg\u00c3\u00b3cios em geral, exceto imobili\u00c3\u00a1rios","46.15-0-00 - Representantes comerciais e agentes do com\u00c3\u00a9rcio de eletrodom\u00c3\u00a9sticos, m\u00c3\u00b3veis e artigos de uso dom\u00c3\u00a9stico","46.14-1-00 - Representantes comerciais e agentes do com\u00c3\u00a9rcio de m\u00c3\u00a1quinas, equipamentos, embarca\u00c3\u00a7\u00c3\u00b5es e aeronaves","46.13-3-00 - Representantes comerciais e agentes do com\u00c3\u00a9rcio de madeira, material de constru\u00c3\u00a7\u00c3\u00a3o e ferragens","46.19-2-00 - Representantes comerciais e agentes do com\u00c3\u00a9rcio de mercadorias em geral n\u00c3\u00a3o especializado"],"natureza_juridica":"206-2 - SOCIEDADE EMPRESARIA LIMITADA","endereco":"R FLORIANO PEIXOTO","numero":"446","complemento":"450","cep":"15.025-110","distrito":"BOA VISTA","municipio":"SAO JOSE DO RIO PRETO","uf":"SP","email":"LOPESDESOUZA@LOPESDESOUZA.COM.BR","telefone":"(17) 3211-8600","efr":"*****","situacao":"ATIVA","data_situacao":"28\/07\/2011","situacao_especial":"********","data_situacao_especial":"********"}';

    	$this->assertJson($json);

    	$this->assertEquals($jsonComparation, $json);
    }
}