<?php
/**
 * Created by PhpStorm.
 * User: isaque
 * Date: 23/01/2018
 * Time: 20:10
 */

namespace Delco\Model\DAL;

use Delco\Util\DBLayer;
use Delco\Util\Utils;



class MenuDAL
{
    private $db = null;

    function __construct()
    {
        $this->db = DBLayer::Connect();
    }

    public function getHierarchyForUser()
    {

    }



}