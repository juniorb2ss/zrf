<?php
namespace zRF\Query;

use Exception;
use zRF\Query\Service\Client;
use zRF\Query\Service\Curl;
use zRF\Query\Utils\Utils as Utils;
use zRF\Query\Crawler\Crawler;
use Intervention\Image\ImageManagerStatic as Image;
use GuzzleHttp\Cookie\SetCookie;
use zRF\Query\Exception\InvalidInputs;
use zRF\Query\Exception\InvalidCaptcha;
use zRF\Query\Exception\NoCaptchaResponse;
use zRF\Query\Exception\NoServiceResponse;
/**
*
*/
class Search
{
    /**
     * [$instance description]
     * @var [type]
     */
    private static $instance;

    /**
     * [$cookie description]
     * @var [type]
     */
    private static $cookie;

    /**
     * [$document description]
     * @var [type]
     */
    private static $document;

    /**
     * [$reponse description]
     * @var [type]
     */
    private static $response;

    /**
     * [$captcha description]
     * @var [type]
     */
    private static $captcha;

    /**
     * [$image description]
     * @var [type]
     */
    private static $image;

    /**
     * [$erroSelector description]
     * @var string
     */
    private static $erroSelector = 'body > table:nth-child(3) > tr:nth-child(2) > td > b > font';

    /**
     * Metodo para capturar o captcha e cookie para ser enviado para próxima consulta
     *
     * Este método basicamente faz uma chamada primária para o serviço, capturando o cookie da requisição
     * este mesmo cookie deverá ser informado para as requisições posteriores, mantendo integridade sempre do
     * mesmo captcha e consulta.
     *
     * @param  string $cnpj CNPJ da empresa que deverá ser consultado
     * @throws Exception
     * @return array Link para ver o Captcha e Cookie
     */
    private static function getParams() {
        // instancia o client http
        self::$instance = new Client();

        // Executa um request para URL do serviço, retornando o cookie da requisição primária
        self::$instance->request('GET', 'http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao2.asp');

        // Captura o cookie da requisição, será usuado posteriormente
        self::$cookie = self::$instance->cookie();

        // Inicia instancia do cURL
        $curl = new Curl;

        // Inicia uma requisição para capturar a imagem do captcha
        // informando cookie da requisição passada e os headers
        //
        // to-do: implementar guzzlehttp?
        // ele é melhor que o curl? ou mais organizado?
        $curl->init('http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/captcha/gerarCaptcha.asp');

        // headers da requisição
        $curl->options([
                        CURLOPT_COOKIEJAR => 'cookiejar',
                        CURLOPT_HTTPHEADER => array(
                            "Pragma: no-cache",
                            "Origin: http://www.receita.fazenda.gov.br",
                            "Host: www.receita.fazenda.gov.br",
                            "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
                            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                            "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3",
                            "Accept-Encoding: gzip, deflate",
                            "Referer: http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao2.asp",
                            "Cookie: flag=1; ". self::$cookie,
                            "Connection: keep-alive"
                        ),
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => 1,
                        CURLOPT_BINARYTRANSFER => TRUE,
                        CURLOPT_CONNECTTIMEOUT => 10,
                        CURLOPT_TIMEOUT => 10,
                ]);

        // executa o curl, logo após fechando a conexão
        $curl->exec();
        $curl->close();

        // captura do retorno do curl
        // o esperado deverá ser o HTML da imagem
        self::$captcha = $curl->response();

        // é uma imagem o retorno?
        if(@imagecreatefromstring(self::$captcha) == false)
        {
            throw new NoCaptchaResponse('Não foi possível capturar o captcha');
        }

        // constroe o base64 da imagem para o usuário digitar
        // to-do: um serviço automatizado para decifrar o captcha?
        // talvez deathbycaptcha?
        self::$image = 'data:image/png;base64,' . base64_encode(self::$captcha);
    }

    /**
     * Retorna string da imagem do captcha capturado anteriormente.
     *
     * @return string Base64_encode($image)
     */
    public static function image($returnUrl = false)
    {
        # has instance?
        if(!self::$instance){
           self::getParams();
        }

        $imageBase64 = self::$image;
        $path = null;

        return ['image' => ($returnUrl) ? ['url' => url($path), 'local' => $path] : $imageBase64 ];
    }

    /**
     * Retorna string do cookie capturado anteriormente.
     * @return string $cookie
     */
    public static function cookie()
    {
        # has instance?
        if(!self::$instance){
           self::getParams();
        }

        return self::$cookie;
    }

    /**
     * Salva imagem no caminho configurado para chamada pelo deathbycaptcha.com
     * @param string $imagem base64_image
     * @return string path
     */
    private function saveImage($image)
    {

    }

    /**
     * Método responsável por fazer chamada no serviço
     * informando CNPJ, captcha resolvido e a string do cookie
     * da chamada anterior
     * @param  integer $cnpj        CNPJ da empresa a ser consultado
     * @param  string $captcha      Captcha resolvido pelo usuário
     * @param  string $stringCookie String do cookie a ser utilizado na chamada
     * @return string HTML para scrapping
     */
    public static function search($cnpj, $captcha, $stringCookie)
    {
        if(!Utils::isCnpj($cnpj)){
            throw new InvalidInputs('CNPJ informado é inválido', 99);
        }

        // prepara o form
        $postParams = [
            'origem' => 'comprovante',
            'cnpj' => Utils::unmask($cnpj), // apenas números
            'txtTexto_captcha_serpro_gov_br' => $captcha,
            'submit1' => 'Consultar',
            'search_type' => 'cnpj'
        ];

        // inicia o cURL
        $curl = new Curl;

        // vamos registrar qual serviço será consultado
        $curl->init('http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/valida.asp');

        // define os headers para requisição curl.
        $curl->options(
            array(
                CURLOPT_HTTPHEADER => array(
                    "Pragma: no-cache",
                    "Origin: http://www.receita.fazenda.gov.br",
                    "Host: www.receita.fazenda.gov.br",
                    "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:32.0) Gecko/20100101 Firefox/32.0",
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                    "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3",
                    "Accept-Encoding: gzip, deflate",
                    'Referer: http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/Cnpjreva_Solicitacao2.asp?cnpj='. $cnpj,
                    "Cookie: flag=1; ". $stringCookie,
                    "Connection: keep-alive"
                ),
                CURLOPT_RETURNTRANSFER  => 1,
                CURLOPT_BINARYTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => 1,
            )
        );

        // efetua a chamada passando os parametros de form
        $curl->post($postParams);
        $curl->exec();

        // completa a chamda
        $curl->close();

        // vamos capturar retorno, que deverá ser o HTML para scrapping
        $html = $curl->response();

        if(empty($html)) {
            throw new NoServiceResponse('No response from service', 99);
        }

        $crawler = new Crawler($html);

        // CNPJ informado é válido?
        if($crawler->filter('#imgCaptcha')->count()){
            throw new InvalidCaptcha('Captcha inválido', 99);
        }

        // verifica se a página seguida na requisição 
        // é página de erro da receita federal
        $error = $crawler->filter(self::$erroSelector);

        if($error->count()){
             throw new InvalidInputs(trim($error->text()), 99);
        }

        return $crawler;
    }
}
