<?php

/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 15/07/2017
 * Time: 16:37
 */


/**
 * Correios RESTful API
 * API para desenvolvedores utilizarem em aplicações que precisem encontrar um endereço a partir do CEP informado.
 * http://correiosapi.apphb.com/cep/76873274
 **/

namespace Delco\Util;

use \phpQuery;
use \Exception;
use \DOMDocument;
use \DOMXPath;
use Delco\Util\Exceptions\LimitExceededException;
use Delco\Util\Exceptions\ForbiddenException;
use Delco\Util\Exceptions\NoContentException;
use Delco\Util\Exceptions\NotFoundException;

class Correios
{
    //Obtem CEPs a partir de um endereço utilizando o site mobile dos correios
    //fora do ar
    public static function GetCEPVersion1($endereco = 'av brasil')
    {
        /**
         * <input type="hidden" name="metodo" value="buscarCep" id="metodo" >
         * <input type="hidden" name="numPagina" value="1">
         * <input type="hidden" name="regTotal" value="101">
         * <input type="hidden" name="cepEntrada" value="Rua Santa Catarina" id="cepEntrada">
         * <input type="hidden" name="tipoCep" value="" id="tipoCep">
         * <input type="hidden" name="cepTemp" value="" id="cepTemp">
         */
        $result = array();
        $result['draw'] = '1';
        $result['recordsTotal'] = '10';
        $result['recordsFiltered'] = '10';
        $result['status'] = '200';
        $result['data'] = array();
        $valueProc = 'buscarCep';

        try
        {
            $BASE_URL = 'http://m.correios.com.br/movel/buscaCepConfirma.do';
            $parameters = array('cepEntrada' => $endereco, 'tipoCep' => '', 'cepTemp' => '', 'metodo' => $valueProc);

            $html = Utils::SimpleCurl($BASE_URL, $parameters);
            phpQuery::newDocumentHTML($html, $charset = 'utf-8');
            $pq_form = pq('');
            //$pq_form = pq('.divopcoes,.botoes',$pq_form)->remove();

            //$result['draw'] = pq('input[name=numPagina]')->attr('value');
            //$result['recordsTotal'] = pq('input[name=regTotal]')->attr('value');

            foreach (pq('#frmCep > div') as $pq_div)
            {
                if (pq($pq_div)->is('.caixacampobranco') || pq($pq_div)->is('.caixacampoazul'))
                {
                    $dados = array();
                    //$dados['cliente'] = trim(pq('.resposta:contains("Cliente: ") + .respostadestaque:eq(0)', $pq_div)->text());

                    if (count(pq('.resposta:contains("Endereço: ") + .respostadestaque:eq(0)', $pq_div)))
                    {
                        //'logradouro'
                        $dados['logradouro'] = trim(pq('.resposta:contains("Endereço: ") + .respostadestaque:eq(0)', $pq_div)->text());
                    }
                    else
                    {
                        //'logradouro'
                        $dados['logradouro'] = trim(pq('.resposta:contains("Logradouro: ") + .respostadestaque:eq(0)', $pq_div)->text());
                        //'bairro'
                        $dados['bairro'] = trim(pq('.resposta:contains("Bairro: ") + .respostadestaque:eq(0)', $pq_div)->text());
                    }
                    $cidadeUF = array();
                    $cidadeUF['cidade/uf'] = trim(pq('.resposta:contains("Localidade") + .respostadestaque:eq(0)', $pq_div)->text());
                    //'cep'
                    $dados['cep'] = trim(pq('.resposta:contains("CEP: ") + .respostadestaque:eq(0)', $pq_div)->text());

                    $cidadeUF['cidade/uf'] = explode('/', $cidadeUF['cidade/uf']);
                    //'cidade'
                    $dados['cidade'] = trim($cidadeUF['cidade/uf'][0]);
                    //'uf' //
                    $dados['uf'] = trim($cidadeUF['cidade/uf'][1]);

                    // unset($dados['cidade/uf']);

                    array_push($result['data'], $dados);
                }
            }
            return $result;

        }
        catch (Exception $ex)
        {
            throw new Exception($ex->getMessage(), $ex->getCode());
        }
    }

    //Obtem CEPs a partir de um endereço utilizando o site desktop dos correios
    public static function GetCEPVersion2($endereco = 'av brasil', $pag = 1, $draw = 1, $limit = 50)
    {
        //PARAMETROS POST
        /**
         * relaxation:avenida brasil
         * exata:S
         * semelhante:N
         * tipoCep:ALL
         * qtdrow:50
         * pagini:51
         * pagfim:100
         **/

        $BASE_WEBSERVICE_URL = 'http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm';

        $qtdrow = $limit;
        $pagfim = ($pag * $qtdrow);//100 50
        $pagini = ($pagfim - $qtdrow) + 1;//51 1

        $result = null;
        try
        {
            $parameters = array('relaxation' => Utils::RemoveAccents($endereco), 'tipoCEP' => 'ALL', 'semelhante' => 'N', 'qtdrow' => $qtdrow, 'pagini' => $pagini, 'pagfim' => $pagfim);

            $html = Utils::SimpleCurl($BASE_WEBSERVICE_URL, $parameters, 'POST', 'formData');

            if (strpos($html, 'not found') !== false)
            {
                throw new NotFoundException();
            }

            if (strpos($html, 'Forbidden') !== false)
            {
                throw new ForbiddenException();
            }

            if (strpos($html, 'robô') !== false)
            {
                throw new LimitExceededException();
            }

            if (strpos($html, 'DADOS NAO ENCONTRADOS') !== false)
            {
                throw new NoContentException();
            }

            //
            if (strpos($html, 'HTTP verb') !== false)
            {
                throw new Exception('HTTP verb, O verbo HTTP usado para acessar esta página não é permitido', 405);
            }

            //Expresão regular para remover quebra de linha vazia
            $html = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $html);
            //Remove TAG HTML de espaço em brando
            $html = str_replace('&nbsp;', '', $html);

            phpQuery::newDocumentHTML($html, $charset = 'utf-8');

            //Inicializa array dos resultados dos correios
            $result = array();
            $result['draw'] = $draw;
            $result['recordsTotal'] = '50';
            $result['recordsFiltered'] = '50';
            $result['status'] = '200';
            $result['qtdrow'] = '50';
            $result['pagini'] = '1';
            $result['pagfim'] = '50';

            $result['qtdrow'] = $qtdrow;
            $result['pagini'] = $pagini;
            $result['pagfim'] = $pagfim;
            $result['qtdPag'] = '1';
            $result['data'] = array();
            $trCount = 0;

            try
            {
                $stringTotalReg = pq('.ctrlcontent')->text();
                $startPos = strpos($stringTotalReg, '[ Nova Consulta ]');
                if ($startPos !== false)
                {
                    $string = substr($stringTotalReg, $startPos + 27, 50);
                    $string2 = substr($string, 0, strpos($string, "\n") - 2);
                    $string2 = explode(' ', $string2);
                    $result['recordsTotal'] = $string2[4];
                    $result['qtdPag'] = $string2[4] / $qtdrow;
                    $result['recordsFiltered'] = $string2[4];
                }
            }
            catch (Exception $e)
            {
            }

            //Obtem todas as linhas da tabela
            $table = pq('.tmptabela > tr');

            if ($table == '')
            {
                throw new Exception('Gone, Este serviço mudou ou esta indisponivel, entre em contato com o suporte técnico', 410);
            }
            foreach ($table as $pq_tr)
            {

                if ($trCount > 0)
                {
                    $tr = pq($pq_tr)->html();
                    $tdArray = array();
                    $tdArray['tipo'] = '';
                    $tdArray['logradouro'] = '';
                    $tdArray['complemento'] = '';
                    $tdArray['logradouroFull'] = '';
                    $tdArray['bairro'] = '';
                    $tdArray['localidade'] = '';
                    $tdArray['uf'] = '';
                    $tdArray['cep'] = '';
                    $tdCount = 0;
                    //obtem todas as colunas

                    foreach (pq($tr) as $pq_td)
                    {
                        $tdText = pq($pq_td)->text();

                        if ($tdText != "\r\r" && $tdText != "\n" && $tdText != "\r")
                        {
                            switch ($tdCount)
                            {
                                case 0:

                                    $pattern = '/[,\-(]/';
                                    $tipo = 'Travessa';
                                    $strPosTipo = strpos($tdText, $tipo);
                                    if ($strPosTipo !== false && $strPosTipo != 0)
                                    {
                                        $preTipo = trim(substr($tdText, 0, $strPosTipo));
                                        $postTipo = trim(substr($tdText, $strPosTipo + strlen($tipo)));
                                        $tdArray['tipo'] = $tipo;
                                        $tipoLogradouroComplemento = preg_split($pattern, $postTipo, 2);
                                        $tdArray['logradouro'] = $preTipo . ', ' . trim(@$tipoLogradouroComplemento[0]);
                                        $tdArray['complemento'] = trim(@$tipoLogradouroComplemento[1]);
                                    }
                                    else
                                    {
                                        //$replacement = '###';
                                        //$arrayS = preg_replace($pattern, $replacement, $tdText, 1);
                                        //$arrayS = explode('###', $string,2);
                                        $tipoLogradouroComplemento = preg_split($pattern, $tdText, 2);
                                        $tipoLogradouro = explode(' ', $tipoLogradouroComplemento[0], 2);
                                        $tdArray['tipo'] = $tipoLogradouro[0];
                                        $tdArray['logradouro'] = trim(@$tipoLogradouro[1]);
                                        $tdArray['complemento'] = trim(@$tipoLogradouroComplemento[1]);
                                    }
                                    $tdArray['logradouroFull'] = $tdText;

                                    break;
                                case 1:
                                    $tdArray['bairro'] = $tdText;
                                    break;
                                case 2:
                                    $CidadeEstado = explode('/', $tdText);
                                    $tdArray['localidade'] = $CidadeEstado[0];
                                    $tdArray['uf'] = Utils::$StatesOfBrazil[substr(trim($CidadeEstado[1]), 0, 2)];
                                    break;
                                case 3:
                                    $tdArray['cep'] = $tdText;
                                    break;
                            }
                            $tdCount++;
                        }
                    }
                    array_push($result['data'], $tdArray);
                }
                $trCount++;
            }
            return $result;
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public static function GetCEPVersion2inJson($endereco = 'av brasil', $pag = 1, $draw = 1, $limit = 50)
    {
        return json_encode(self::GetCEPVersion2($endereco, $pag, $draw, $limit));
    }

    //obtem o endereço a partir do CEP utilizando a API correiosapi.apphb.com
    public static function GetEndereco($cep = '28890130')
    {
        /*$BASE_WEBSERVICE_URL = 'http://correiosapi.apphb.com/cep/';

        $result = array();
        try
        {
            $result['cep'] = '';
            $result['tipo'] = '';
            $result['logradouro'] = '';
            $result['bairro'] = '';
            $result['municipio'] = '';
            $result['uf'] = '';
            //trim(str_replace('-','',$cep))

            $curlResponse = Utils::SimpleCurl($BASE_WEBSERVICE_URL, $cep, 'GET');
            $responseArray = json_decode($curlResponse,true);

            if($responseArray)
            {
                $result['cep'] = $responseArray['cep'];
                $result['tipo'] = $responseArray['tipoDeLogradouro'];
                $result['logradouro'] = $responseArray['logradouro'];
                $result['bairro'] = $responseArray['bairro'];
                $result['municipio'] = $responseArray['cidade'];
                $result['uf'] = Utils::$StatesOfBrazil[trim($responseArray['estado'])];
            }
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        }
        return $result;*/

        return self::GetEnderecoByCEPWebmaniabr($cep);
    }
    //obtem o endereço a partir do CEP utilizando a API Webmaniabr
    public static function GetEnderecoByCEPWebmaniabr($cep = '28890130')
    {
        $BASE_WEBSERVICE_URL = 'https://webmaniabr.com/api/1/cep/' . $cep . '/';

        $result = array();
        try
        {
            $result['cep'] = '';
            $result['tipo'] = '';
            $result['logradouro'] = '';
            $result['bairro'] = '';
            $result['municipio'] = '';
            $result['uf'] = '';
            //trim(str_replace('-','',$cep))
            //chave da API
            $parameters = array('app_key' => 'TpoYKDPMyaxoI3f2MPBQm5pwtXuOn4w0', 'app_secret' => '5AMLfYv0NaqK039ku1qxNwsy5HeZIw5gyZUvkNWUmbc4m6do');

            $curlResponse = Utils::SimpleCurl($BASE_WEBSERVICE_URL, $parameters, 'GET', 'formData');
            $responseArray = json_decode($curlResponse, true);

            if ($responseArray)
            {
                $result['cep'] = $responseArray['cep'];
                $result['tipo'] = explode(' ', trim($responseArray['endereco']))[0];
                $result['logradouro'] = trim(strstr($responseArray['endereco'], " "));
                $result['bairro'] = $responseArray['bairro'];
                $result['municipio'] = $responseArray['cidade'];
                $result['uf'] = Utils::$StatesOfBrazil[trim($responseArray['uf'])];
            }
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
        return $result;
    }
    //obtem o endereço a partir do CEP utilizando o site dos correios mobile
    //fora do ar
    public static function GetEnderecoByCEPMobileCorreios($cep)
    {
        $response = file_get_contents(
            'http://m.correios.com.br/movel/buscaCepConfirma.do',
            false,
            stream_context_create([
                'http' => [
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query([
                        'cepEntrada' => $cep,
                        'metodo' => 'buscarCep',
                    ]),
                ],
            ])
        );

        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        $values = $xpath->query('//*[@class="respostadestaque"]');
        $result = [];

        // Se não encontrar CEP, retorna false
        if (!$values->length) {
            return false;
        }

        // Obtém informações desejadas, tratando-as quando necessário
        foreach ($values as $value) {
            $result[] = preg_replace(
                '~[\s]{2,}~',
                '',
                trim($value->childNodes->item(0)->nodeValue)
            );
        }

        if ($values->length > 2) {
            // CEPs de logradouros
            list($logradouro, $bairro, $localidade, $cep) = $result;
        } else {
            // CEPs de localidades
            list($logradouro, $bairro) = null;
            list($localidade, $cep) = $result;
        }

        list($localidade, $uf) = explode('/', $localidade);

        return compact('logradouro', 'bairro', 'localidade', 'uf', 'cep');
    }

    public static function Rastreio($codigo)
    {
        $html = Utils::SimpleCurl('http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=' . $codigo);
        phpQuery::newDocumentHTML($html, $charset = 'utf-8');

        $rastreamento = array();
        $c = 0;
        foreach (pq('tr') as $tr)
        {
            $c++;
            if (count(pq($tr)->find('td')) == 3 && $c > 1)
            {
                $rastreamento[] = array('data' => pq($tr)->find('td:eq(0)')->text(), 'local' => pq($tr)->find('td:eq(1)')->text(), 'status' => pq($tr)->find('td:eq(2)')->text());
            }
        }
        if (!count($rastreamento))
        {
            return false;
        }
        return $rastreamento;
    }

}

