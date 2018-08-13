<?php

namespace Delco\Model\VO;

class BugReport
{
    const TABLE_NAME = "bug_reports";
    const KEY_ID = "id";
    const SISTEMA = "sistema";
    const PAGINA = "pagina";
    const DESCRICAO_PROBLEMA = "descricaoProblema";
    const SCRENSHOT = "screenshot";
    //const DATA_ENVIADO = "dataEnviado";

    const TABLE_FIELDS = [
        self::SISTEMA,
        self::PAGINA,
        self::DESCRICAO_PROBLEMA,
        self::SCRENSHOT
    ];

    public $id;
    public $sistema;
    public $pagina;
    public $descricaoProblema;
    public $screenshot;

}

