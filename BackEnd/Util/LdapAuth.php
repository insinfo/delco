<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 30/08/2017
 * Time: 17:24
 */

namespace Delco\Util;

use \Exception;
use Delco\Util\Utils;

class LdapAuth
{
    private $ldapHost = 'ldap://192.168.133.10';//active airectory server ex "server.college.school.edu"
    private $ldapPort = '';
    private $ldapDomain = 'DC=dcro,DC=gov';//active directory domain name DN (base location of ldap search) ex "OU=Departments,DC=college,DC=school,DC=edu"
    private $ldapUserGroup = null;// active directory user group name ex  KAnboard_users
    private $ldapManagerGroup = null;// active directory manager group name ex Kanboard_gerente
    private $ldapUserDomain = '@dcro.gov';// domain, for purposes of constructing $user ex '@college.school.edu'
    private $ldapConnection = null;
    private $authenticatedUser = null;

    public function setHost($ldapHost)
    {
        $this->ldapHost = $ldapHost;
    }

    public function setPort($ldapPort)
    {
        $this->ldapPort = $ldapPort;
    }

    public function setDomain($ldapDomain)
    {
        $this->ldapDomain = $ldapDomain;
    }

    public function setUserGroup($ldapUserGroup)
    {
        $this->ldapUserGroup = $ldapUserGroup;
    }

    public function setManagerGroup($ldapManagerGroup)
    {
        $this->ldapManagerGroup = $ldapManagerGroup;
    }

    public function setUserDomain($ldapUserDomain)
    {
        $this->ldapManagerGroup = $ldapUserDomain;
    }

    private function connect()
    {
        return $this->ldapConnection = ldap_connect($this->ldapHost);
    }

    /**
     * @param $user
     * @param $password
     * @return bool
     * @throws Exception
     */
    public function authenticate($user, $password)
    {
        $result = false;
        if (Utils::IsNullOrEmptyString($user))
        {
            throw new Exception('Usuario não pode ser nulo ou vazio!', 400);
        }
        if (Utils::IsNullOrEmptyString($password))
        {
            throw new Exception('Senha não pode ser nula ou vazia!', 400);
        }

        $this->connect();
        $ldaprdn = $user . $this->ldapUserDomain;
        ldap_set_option($this->ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->ldapConnection, LDAP_OPT_REFERRALS, 0);

        try
        {
            // verifica usuario e senha
            $bind = ldap_bind($this->ldapConnection, $ldaprdn, $password);
            if ($bind)
            {
                $result = true;
                $this->authenticatedUser = $user;
            }
        }
        catch (Exception $e)
        {
            //credencial invalida
            // nome ou senha invalido
            throw new Exception('Credenciais inválidas', 401);
        }

        return $result;
    }

    public function getAllUser()
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }
        $conn = $this->ldapConnection;
        $base = $this->ldapDomain;
        //$filter="(cn=*)";
        //$filter="(sAMAccountName=*)";
        $filter = "(&(objectClass=user)(objectCategory=person)(sn=*))";
        $justthese = array();
        $pageSize = 300;
        $cookie = '';
        $count = 0;
        $data = array();
        do
        {
            ldap_control_paged_result($conn, $pageSize, true, $cookie);

            $result = ldap_search($conn, $base, $filter, $justthese);
            $entries = ldap_get_entries($conn, $result);

            if (!empty($entries))
            {
                for ($i = 0; $i < $entries["count"]; $i++)
                {
                    $data[] = array('name' => $entries[$i]["cn"][0], 'username' => $entries[$i]["samaccountname"][0]);
                    $count++;
                }
            }
            ldap_control_paged_result_response($conn, $result, $cookie);

        }
        while ($cookie !== null && $cookie != '');
        array_multisort($data);//ordena o array alfabeticamente
        return $data;
    }

    public function countRegisteredUsers()
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        return count($this->getAllUser());
    }

    public function countTotalNumberOfPages($recordsPerPage)
    {
        $totalRecords = $this->countRegisteredUsers();
        $totalNumberOfPages = ceil($totalRecords / $recordsPerPage);
        return $totalNumberOfPages;
    }

    public function getAllUserByLimit($correntPage = 1, $recordsPerPage = 50)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }
        $lista = array();

        $entries = $this->getAllUser();
        $entriesCount = count($entries);

        if ($correntPage < 0)
        {
            $correntPage = 0;
        }
        if ($correntPage == 0)
        {
            $correntPage = $correntPage + 1;
        }
        $totalRecords = $entriesCount;
        $totalNumberOfPages = ceil($totalRecords / $recordsPerPage);
        // calculando início e fim da seleção
        $qtdrow = $recordsPerPage;
        $pagfim = ($correntPage * $qtdrow);//72*10 =720
        $pagini = ($pagfim - $qtdrow) + 1;//720-10+1=711

        if ($correntPage > $totalNumberOfPages)
        {
            return null;
        }

        for ($i = $pagini - 1; $i < $pagfim; $i++)
        {
            if ($i == $totalRecords)
            {
                break;
            }
            $item = array();
            $item['nome'] = $entries[$i]['name'];
            $item['login'] = $entries[$i]['username'];
            array_push($lista, $item);
        }
        return $lista;
    }

    /** procura usuario pelo nome **/
    public function findUserByName($name, $limit = null)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        $lista = array();
        $entries = $this->getAllUser();
        $entriesCount = count($entries);

        $name = strtolower(Utils::removeAccents($name));

        for ($i = 0; $i < $entriesCount; $i++)
        {

            if (strpos(strtolower($entries[$i]['name']), $name) !== false)
            {
                $item = array();
                $item['nome'] = $entries[$i]['name'];
                $item['login'] = $entries[$i]['username'];
                array_push($lista, $item);
            }
            if ($limit != null)
            {
                if ($i == $limit)
                {
                    break;
                }
            }
        }

        return $lista;
    }

    /**alteracao de senha no ldap**/
    public function changePassword($newPassword)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }
        $ldaprdn = $this->authenticatedUser . $this->ldapUserDomain;

        $info["userPassword"] = $newPassword;

        $rs = ldap_mod_replace($this->ldapConnection, $ldaprdn, $info);

        if ($rs)
        {
            return true;
        }
        return false;
    }

    public function createNewUser($conpleteName, $username, $password, $email = '')
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        // Prepare data
        $info['cn'] = $conpleteName;
        $info['sn'] = " ";
        $info['mail'] = $email;
        $info['objectclass'] = "inetOrgPerson";
        $info['userpassword'] = $password;
        $info['samaccountname'] = $username;

        // Add data to directory
        $rs = ldap_add($this->ldapConnection, "cn=$conpleteName," . $this->ldapDomain, $info);
        ldap_close($this->ldapConnection);
        if ($rs)
        {
            return true;
        }
        return false;
    }

    public function deleteUser($userName)
    {
        if ($this->ldapConnection == null && $this->authenticatedUser == null)
        {
            new Exception('Autentique primeiro!', 400);
        }

        $base = $this->ldapDomain;
        $filter = "(sAMAccountName=$userName)";
        $result = ldap_search($this->ldapConnection, $base, $filter);
        $info = ldap_get_entries($this->ldapConnection, $result);
        $dn = $info[0]["dn"];
        $rs = ldap_delete($this->ldapConnection, $dn);
        ldap_close($this->ldapConnection);

        if ($rs)
        {
            return true;
        }
        return false;
    }


}