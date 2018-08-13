<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 08/03/2018
 * Time: 09:43
 */

namespace Delco\Model\VO;

class Token
{
    public $idSistema = -1;
    public $idPessoa = -1;
    public $idOrganograma = -1;
    public $idPerfil = -1;
    public $loginName = '';
    public $domain = '';
    public $tokenType = 'bearer';
    public $pws = '';
    public $ipClientPrivate = '';
    public $ipClientPublic = '';
    public $ipClientVisibleByServer = '';
    public $host = '';
    public $origen = '';
    public $userAgent = '';

    //JWT
    public $iat;
    public $iss;
    public $exp;
    public $nbf;
    public $data;

    function __construct($request=null)
    {
        if ($request != null)
        {
            $jwt = $request->getAttribute('jwt');
            $jwtArray = array();
            $this->objToArray($jwt, $jwtArray);

            $this->iat = $jwtArray['iat'];
            $this->iss = $jwtArray['iss'];
            $this->exp = $jwtArray['exp'];
            $this->nbf = $jwtArray['nbf'];
            $this->data = $jwtArray['data'];
            $this->domain = $this->iss;

            $this->idSistema = $this->data['idSistema'];
            $this->idOrganograma = $this->data['idOrganograma'];
            $this->loginName = $this->data['loginName'];
            $this->idPerfil = $this->data['idPerfil'];
            $this->idPessoa = $this->data['idPessoa'];
            $this->pws = $this->data['pws'];
            $this->ipClientPrivate = $this->data['ipClientPrivate'];
            $this->ipClientPublic = $this->data['ipClientPublic'];
            $this->ipClientVisibleByServer = $this->data['ipClientVisibleByServer'];
            $this->host = $this->data['host'];
            $this->origin = $this->data['origin'];
            $this->userAgent = $this->data['userAgent'];
        }
    }

    function fillFromJwt($jwt)
    {
        $jwtArray = array();
        $this->objToArray($jwt, $jwtArray);

        $this->iat = $jwtArray['iat'];
        $this->iss = $jwtArray['iss'];
        $this->exp = $jwtArray['exp'];
        $this->nbf = $jwtArray['nbf'];
        $this->data = $jwtArray['data'];
        $this->domain = $this->iss;

        $this->idSistema = $this->data['idSistema'];
        $this->idOrganograma = $this->data['idOrganograma'];
        $this->loginName = $this->data['loginName'];
        $this->idPerfil = $this->data['idPerfil'];
        $this->idPessoa = $this->data['idPessoa'];
        $this->pws = $this->data['pws'];
        $this->ipClientPrivate = $this->data['ipClientPrivate'];
        $this->ipClientPublic = $this->data['ipClientPublic'];
        $this->ipClientVisibleByServer = $this->data['ipClientVisibleByServer'];
        $this->host = $this->data['host'];
        $this->origin = $this->data['origin'];
        $this->userAgent = $this->data['userAgent'];
    }

    public function objToArray($obj, &$arr)
    {
        if (!is_object($obj) && !is_array($obj)) {
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value) {
            if (!empty($value)) {
                $arr[$key] = array();
                $this->objToArray($value, $arr[$key]);
            } else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * @return int
     */
    public function getIdSistema()
    {
        return $this->idSistema;
    }

    /**
     * @param int $idSistema
     */
    public function setIdSistema($idSistema)
    {
        $this->idSistema = $idSistema;
    }

    /**
     * @return int
     */
    public function getIdPessoa()
    {
        return $this->idPessoa;
    }

    /**
     * @param int $idPessoa
     */
    public function setIdPessoa($idPessoa)
    {
        $this->idPessoa = $idPessoa;
    }

    /**
     * @return int
     */
    public function getIdOrganograma()
    {
        return $this->idOrganograma;
    }

    /**
     * @param int $idOrganograma
     */
    public function setIdOrganograma($idOrganograma)
    {
        $this->idOrganograma = $idOrganograma;
    }

    /**
     * @return int
     */
    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * @param int $idPerfil
     */
    public function setIdPerfil($idPerfil)
    {
        $this->idPerfil = $idPerfil;
    }

    /**
     * @return string
     */
    public function getLoginName()
    {
        return $this->loginName;
    }

    /**
     * @param string $loginName
     */
    public function setLoginName($loginName)
    {
        $this->loginName = $loginName;
    }

    /**
     * @return mixed|string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed|string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getPws()
    {
        return $this->pws;
    }

    /**
     * @param string $pws
     */
    public function setPws($pws)
    {
        $this->pws = $pws;
    }

    /**
     * @return string
     */
    public function getIpClientPrivate()
    {
        return $this->ipClientPrivate;
    }

    /**
     * @param string $ipClientPrivate
     */
    public function setIpClientPrivate($ipClientPrivate)
    {
        $this->ipClientPrivate = $ipClientPrivate;
    }

    /**
     * @return string
     */
    public function getIpClientPublic()
    {
        return $this->ipClientPublic;
    }

    /**
     * @param string $ipClientPublic
     */
    public function setIpClientPublic($ipClientPublic)
    {
        $this->ipClientPublic = $ipClientPublic;
    }

    /**
     * @return string
     */
    public function getIpClientVisibleByServer()
    {
        return $this->ipClientVisibleByServer;
    }

    /**
     * @param string $ipClientVisibleByServer
     */
    public function setIpClientVisibleByServer($ipClientVisibleByServer)
    {
        $this->ipClientVisibleByServer = $ipClientVisibleByServer;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * @param string $origen
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return mixed
     */
    public function getIat()
    {
        return $this->iat;
    }

    /**
     * @param mixed $iat
     */
    public function setIat($iat)
    {
        $this->iat = $iat;
    }

    /**
     * @return mixed
     */
    public function getIss()
    {
        return $this->iss;
    }

    /**
     * @param mixed $iss
     */
    public function setIss($iss)
    {
        $this->iss = $iss;
    }

    /**
     * @return mixed
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * @param mixed $exp
     */
    public function setExp($exp)
    {
        $this->exp = $exp;
    }

    /**
     * @return mixed
     */
    public function getNbf()
    {
        return $this->nbf;
    }

    /**
     * @param mixed $nbf
     */
    public function setNbf($nbf)
    {
        $this->nbf = $nbf;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


}