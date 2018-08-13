<?php

/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 15/07/2017
 * Time: 12:52
 */

namespace Delco\Util;

use \Exception;

class GoogleMatrix
{
    private $rows; //array(Row)
    private $status; //String
    private $API_KEY;
    private $destinationAddresses;
    private $originAddresses;
    private $distanceValue;
    private $distanceText;
    private $durationValue;
    private $durationText;

    function __construct($api_key = 'AIzaSyAm9MAU-8HxBBXHkLbNBv9tfmJW1_UnbwU')
    {
        $this->API_KEY = $api_key;
    }

    public function Init($origen = '03063-000', $destino = '28890-130', $units = 'metric', $mode = 'CAR', $language = 'PT', $sensor = 'false')
    {
        $erroCode = 200;
        try {
            $API_BASE_URL = "https://maps.googleapis.com/maps/api/distancematrix/json";

            /*$data = '?units=' . $units . '&origins=' . $origin . '&destinations=' . $destination .
                '&mode=' . $mode . '&language=' . $language . '&sensor=' . $sensor . '&key=' . $this->API_KEY;*/

            $data = array(
                'units' => $units,
                'origins' => $origen,
                'destinations' => $destino,
                'mode' => $mode,
                'language' => $language,
                'sensor' => $sensor,
                'key' => $this->API_KEY
            );

            $result = $this->CallAPI($API_BASE_URL, 'GET', $data);
            $resultArray = json_decode($result, true);

            $this->destinationAddresses = $resultArray['destination_addresses'][0];
            $this->originAddresses = $resultArray['origin_addresses'][0];
            $this->rows = $resultArray['rows'];
            $this->status = $resultArray['status'];
            $elements = $this->rows[0]['elements'];
            $this->distanceValue = $elements[0]['distance']['value'];
            $this->durationValue = $elements[0]['duration']['value'];
            $this->distanceText = $elements[0]['distance']['text'];
            $this->durationText = $elements[0]['duration']['text'];
        } catch (Exception $ex) {
            $erroCode = '400';
        }
        return $erroCode;
    }

    private function CallAPI($url, $method = 'GET', $data = false)
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

    public function GetDestinationAddresses()
    {
        return $this->destinationAddresses;
    }

    public function GetOriginAddresses()
    {
        return $this->originAddresses;
    }

    public function GetDistanceMT()
    {
        return $this->distanceValue;
    }

    public function GetDistanceKM($arredondar = true, $casasDecimais = 0)
    {
        return ($arredondar) ? round($this->distanceValue / 1000, $casasDecimais) : $this->distanceValue / 1000;
    }

    public function GetDistanceText()
    {
        return $this->distanceText;
    }

    public function GetDurationSeg()
    {
        return $this->durationValue;
    }

    public function GetDurationTimeStamp()
    {
        return gmdate("H:i:s", $this->durationValue);
    }

    public function GetDurationText()
    {
        return $this->durationText;
    }

    private function SegundoParaHora($total)
    {
        $horas = floor($total / 3600);
        $minutos = floor(($total - ($horas * 3600)) / 60);
        $segundos = floor($total % 60);
        return $horas . ":" . $minutos . ":" . $segundos;
    }
}

