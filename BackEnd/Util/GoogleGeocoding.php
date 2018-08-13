<?php

/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 15/07/2017
 * Time: 14:55
 */
namespace Delco\Util;

class GoogleGeocoding
{
    private $API_KEY;
    private $latitude;
    private $longitude;
    private $enderecoFormatado;

    function __construct($api_key = 'AIzaSyAm9MAU-8HxBBXHkLbNBv9tfmJW1_UnbwU')
    {
        $this->API_KEY = $api_key;
    }

    /**
     * components que podem ser filtrados incluem:
     *
     * route  - corresponde ao nome longo ou curto de uma rota.
     * locality - corresponde aos tipos locality e sublocality.
     * administrative_area - corresponde a todos os níveis administrative_area.
     * postal_code - corresponde a postal_code e postal_code_prefix.
     * country  corresponde ao nome do país ou a um código de país ISO 3166-1 de duas letras.
     **/

    /**
     * O campo "status"
     *
     * "OK" indica que nenhum erro ocorreu; o endereço foi analisado e pelo menos um código geográfico foi retornado.
     * ZERO_RESULTS indica que o código geográfico foi bem-sucedido, mas não retornou resultados. Isso poderá ocorrer
     * se o geocodificador receber um address que não existe.
     * "OVER_QUERY_LIMIT" indica que você ultrapassou a cota.
     * "REQUEST_DENIED" indica que a solicitação foi negada.
     * "INVALID_REQUEST" geralmente indica que a consulta (address, components ou latlng) está ausente.
     * "UNKNOWN_ERROR"
     **/
    public function Init($address = 'R. Santa Catarina,1755 - Cidade Praiana, Rio das Ostras - J,Brasil', $language = 'PT', $region = 'BR', $components = 'country:BR')
    {
        $erroCode = 200;
        try
        {
            $format = 'xml';//json or xml
            $API_BASE_URL = 'https://maps.googleapis.com/maps/api/geocode/' . $format;

            $parameters = array(
                'address' => $address,
                'language ' => $language,
                'region ' => $region,
                'components' => $components,
                'key' => $this->API_KEY
            );

            if ($address != NULL) {
                $request_url = sprintf("%s?%s", $API_BASE_URL, http_build_query($parameters));

                $response = @file_get_contents($request_url);
                if (preg_match('#^HTTP/... 4..#', $http_response_header[0])) {
                    $erroCode = 400;
                } else {
                    //echo "ok";
                    $xml = simplexml_load_string($response);
                    $coordinates = $xml->result->geometry->location;
                    $this->latitude =  $coordinates->lat;
                    $this->longitude = $coordinates->lng;
                    $this->enderecoFormatado = $xml->result->formatted_address;
                }
            }

        } catch (Exception $ex)
        {
            $erroCode = '400';
        }
        return $erroCode;
    }


    public function GetLatitude()
    {
        return $this->latitude;
    }

    public function GetLongitude()
    {
        return $this->longitude;
    }
    public function GetEnderecoFormatado()
    {
        return $this->enderecoFormatado;
    }

    function CallAPI($url, $method = 'GET', $data = false)
    {
        //file_get_contents($url);

        ini_set("allow_url_fopen", "On");
        $curl = curl_init();


        switch ($method) {
            case "POST":

                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;

            case "PUT":

                curl_setopt($curl, CURLOPT_PUT, 1);
                break;

            case "GET":

                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
                break;
        }


        // Optional Authentication:
        /*curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");*/

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}

//$geo = new GoogleGeocoding();
//$geo->Init();
//echo 'longitude: '.$geo->GetLongitude();
//echo 'latitude: '.$geo->GetLatitude();