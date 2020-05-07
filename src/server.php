<?php
require_once('vendor/autoload.php');
require_once 'Subscriber.php';
require_once 'Publisher.php';

$routeRaw = trim($_SERVER["REQUEST_URI"], '/');
$route = explode('/', $routeRaw);

$subscribeRaw = file_get_contents('php://input');
$subscribeTarget = json_decode($subscribeRaw);

if (count($route)) {
    processRequest($route, $subscribeTarget);
}

function processRequest($route, $target)
{
    switch ($route[0]) {
        case 'subscribe':
            handleSubscriptionRequest($route[1], $target);
            break;
        case 'publish':
            handlePublishingRequest($route[1], $target);
            break;
        default:
            $channel = getCurrentUrl();
            handleRegistriesRequest($route, $channel);
            break;
    }
}

function handleSubscriptionRequest($topic, $channel)
{
    $subscriber = new Subscriber($topic . '.subscriber.data', $channel->url);
    $subscriber->getSubscriptions();
}

function handlePublishingRequest($topic, $event)
{
    $publisher = new Publisher($topic . '.subscriber.data');
    $publisher->event($event);
}

function handleRegistriesRequest($route, $channel = null)
{
    $files = scandir('./src/dataset');
    $output = [];
    foreach($files as $file) {
        if(strstr($file, '.data')) {
            $registry = getSubscriptionRegistry($file);
            if ($registry->hasChannel($channel)) {
                array_push($output, $registry);
            }
        }
    }
    http_response_code(200);
    header('Content-type: application/json');
    echo json_encode($output) . PHP_EOL;
}

function getSubscriptionRegistry($file)
{
    $raw = file_get_contents('./src/dataset/' . $file);
    $data = json_decode($raw);
    $topic = explode('.', $file)[0];
    $sr = new SubscriptionRegistry($topic);
    $sr->set($data);
    return $sr;
}

function getCurrentUrl()
{
    $protocol = strstr($_SERVER['SERVER_PROTOCOL'], 'HTTPS') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = strlen($_SERVER['REQUEST_URI']) > 1 ? $_SERVER['REQUEST_URI'] : '';
    return $protocol . '://' . $host . $path;
}
