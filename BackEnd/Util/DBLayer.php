<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 10/12/2017
 * Time: 17:24
 */

namespace Delco\Util;

use Illuminate\Database\Capsule\Manager as Capsule;
use \ReflectionClass;
use \ReflectionProperty;
use PDO;

class DBLayer extends Capsule
{
    private static $SELECTED_CONNECTION_SETS = null;
    private static $connection = null;
    const JSON_FORMAT = 'json';
    const ARRAY_FORMAT = 'array';
    const OPERATOR_ILIKE = 'ilike';//operator ilike
    const OPERATOR_EQUAL = '=';//operator =
    const ORDER_DIRE_ASC = 'asc';//order by Direction ASCENDENT
    const ORDER_DIRE_DESC = 'desc';

    public static function Connect($connectionName = DBConfig::DEFAULT_CONNECTION)
    {
        $CONNECTIONS = DBConfig::$CONNECTIONS['connections'];
        self::$SELECTED_CONNECTION_SETS = $CONNECTIONS[$connectionName];

        $capsule = new Capsule;

        // application/config/database.php
        //'fetch' => PDO::FETCH_CLASS,
        // to
        //'fetch' => PDO::FETCH_ASSOC,

        //DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $capsule->setFetchMode(PDO::FETCH_ASSOC);

        //isaque alterou a linha 104 do arquivo Connection.php do diretorio
        //illuminate/database/Connection.php para abilitar um retorno de array do query builder
        //de isso: protected $fetchMode = PDO::FETCH_OBJ;
        //para isso:  protected $fetchMode = PDO::FETCH_ASSOC;

        $capsule->addConnection(self::$SELECTED_CONNECTION_SETS);

        // Make this Capsule instance available globally via static methods
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM
        $capsule->bootEloquent();



        //converte os dados para array
        //collect($data)->map(function($x){ return (array) $x; })->toArray()

        //Se você quer uma matriz, você pode simplesmente adicionar
        // ->all()logo depois ->get()retirar os itens da coleção.
        return $capsule;

    }


    /*
    public static function show()
    {

    }

    public static function storeObj($object)
    {
        $className = get_class($object);
        $reflect = new ReflectionClass($className);

        $props   = $reflect->getProperties(ReflectionProperty::IS_PRIVATE);

        foreach ($props as $prop) {
            print $prop->getName() . "\n";
        }

        $columns = Capsule::connection()->getSchemaBuilder()->getColumnListing($className.'s');

        $id = Capsule::table(User::TABLE_NAME)->insertGetId(['email' => 'john@example.com', 'votes' => 0]);

    }

    public static function update()
    {

    }

    public static function delete()
    {

    }
*/
}


