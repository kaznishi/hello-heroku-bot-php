<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/response.php';

use Symfony\Component\HttpFoundation\Request;
use ResponseGenerator;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

$app->post('/callback', function (Request $request) use ($app) {
    $app['monolog']->addDebug('callback access.');
    $client = new GuzzleHttp\Client();

    $body = json_decode($request->getContent(), true);
    foreach ($body['result'] as $msg) {
        if (!preg_match('/(ぬるぽ|ヌルポ|ﾇﾙﾎﾟ|nullpo)/i', $msg['content']['text'])) {
            continue;
        }

        $responseGenerator = new ResponseGenerator();
        $responseGenerator->response($msg);

    }

    return 'OK';
});

$app->get('/', function() use($app) {
    $app['monolog']->addDebug('logging output.');
    return "Hello,hogehoge";
});

$app->run();

