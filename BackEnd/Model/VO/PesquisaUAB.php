<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 08/05/2018
 * Time: 12:39
 */

namespace Delco\Model\VO;


class PesquisaUAB
{
    const TABLE_NAME = "pesquisa_uab";
    const KEY_ID = "id";
    const NOME = "nome";
    const EMAIL = "email";
    const TELEFONE = "telefone";
    const LOGRADOURO = "logradouro";
    const BAIRRO = "bairro";
    const COMPLEMENTO = "complemento";
    const CIDADE = "cidade";
    const ESTADO = "estado";
    const CURSO01 = "curso01";
    const CURSO02 = "curso02";
    const CURSO03 = "curso03";
    const CURSO04 = "curso04";

    const TABLE_FIELDS = [
        self::NOME,
        self::EMAIL,
        self::TELEFONE,
        self::LOGRADOURO,
        self::BAIRRO,
        self::COMPLEMENTO,
        self::CIDADE,
        self::ESTADO,
        self::CURSO01,
        self::CURSO02,
        self::CURSO03,
        self::CURSO04
    ];

    public $id;
    public $nome;
    public $email;
    public $telefone;
    public $logradouro;
    public $bairro;
    public $complemento;
    public $cidade;
    public $estado;
    public $curso01;
    public $curso02;
    public $curso03;
    public $curso04;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * @param mixed $telefone
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    /**
     * @return mixed
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * @param mixed $logradouro
     */
    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;
    }

    /**
     * @return mixed
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param mixed $bairro
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    /**
     * @return mixed
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param mixed $complemento
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    /**
     * @return mixed
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param mixed $cidade
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    /**
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * @param mixed $estado
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function getCurso01()
    {
        return $this->curso01;
    }

    /**
     * @param mixed $curso01
     */
    public function setCurso01($curso01)
    {
        $this->curso01 = $curso01;
    }

    /**
     * @return mixed
     */
    public function getCurso02()
    {
        return $this->curso02;
    }

    /**
     * @param mixed $curso02
     */
    public function setCurso02($curso02)
    {
        $this->curso02 = $curso02;
    }

    /**
     * @return mixed
     */
    public function getCurso03()
    {
        return $this->curso03;
    }

    /**
     * @param mixed $curso03
     */
    public function setCurso03($curso03)
    {
        $this->curso03 = $curso03;
    }

    /**
     * @return mixed
     */
    public function getCurso04()
    {
        return $this->curso04;
    }

    /**
     * @param mixed $curso04
     */
    public function setCurso04($curso04)
    {
        $this->curso04 = $curso04;
    }


}