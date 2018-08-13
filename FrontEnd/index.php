<?php
/**
 * ARQUIVO DE CONFIGURAÇÃO DE ROTAS
 **/

$ini_array = parse_ini_file('../.env');
//print_r($ini_array);

define('VIEWS_DIR',$ini_array['VIEWS_DIR']);
define('DB_HOST',$ini_array['DB_HOST']);
define('DB_NAME',$ini_array['DB_NAME']);
define('DB_USERNAME',$ini_array['DB_USERNAME']);
define('DB_PASSWORD',$ini_array['DB_PASSWORD']);
define('BASE_DIR',$ini_array['BASE_DIR']);
define('APP_DEPLOY_SECRET',$ini_array['APP_DEPLOY_SECRET']);
define('PROXY',$ini_array['PROXY']);
define('STORAGE_PATH',$ini_array['STORAGE_PATH']);
define('WEB_ROOT_PATH',$ini_array['WEB_ROOT_PATH']);

$BASE_DIR = dirname(__FILE__);
$VIEWS_DIR = $BASE_DIR.'/View';

require_once '../BackEnd/vendor/autoload.php';

use \Slim\Http\Request;
use \Slim\Http\Response;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Delco\Middleware\IpAddressMiddleware;

//instancia o slim
//$app = new \Slim\App;
$app = new \Slim\App([
    'settings' => [
        // Only set this if you need access to route within middleware
        'determineRouteBeforeAppMiddleware' => true
    ]
]);

// obtem um container
$container = $app->getContainer();

// Registra componente no container para abilitar o html render
$container['view'] = function ($container) use ($VIEWS_DIR){
    $view = new Twig($VIEWS_DIR, [
        'cache' => false
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));

    return $view;
};

//abilita Cros Domain
$app->options('/{routes:.+}', function (Request $request, Response $response, $args) {
    return $response->withStatus(200);
});

$app->add(function ($req, $res, $next) {

    $response = $next($req, $res);
    //$origin = $req->getHeader('Host') ? $req->getHeader('Host') : 'http://192.168.133.12';
    $origin = $req->getHeader('Origin') ? $req->getHeader('Origin') : 'http://192.168.133.12';

    return $response
        ->withHeader('Access-Control-Allow-Credentials', 'true')
        ->withHeader('Access-Control-Allow-Origin', $origin)
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


//REGISTRA O MIDDLEWARE IP_ADDRES
$checkProxyHeaders = false; // Note: Never trust the IP address for security processes!
$trustedProxies = ['192.168.66.111']; // Note: Never trust the IP address for security processes!
$app->add(new IpAddressMiddleware($checkProxyHeaders, $trustedProxies));

// Render html em rota
// ROTAS DE WEBPAGES
require_once '../BackEnd/Routes/web.php';

// ROTAS DE WEBSERVICE REST
//require_once '../BackEnd/Routes/webservice.php';

$app->run();