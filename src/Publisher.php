<?php
require_once 'SubscriptionRegistry.php';

class Publisher
{
    public string $source;
    public string $filePath;
    public object $data;

    public function __construct($source)
    {
        $this->source = explode('.', $source)[0];
        $this->filePath = './src/dataset/' . $source;
        $this->initialize();
    }

    public function initialize()
    {
        if (!file_exists($this->filePath)) {
            http_response_code(400);
            header('Content-type: application/json');
            echo '{ "errorCode": 400, "msg": "Not an existing topic ' . $this->source . '."}' . PHP_EOL;
        }
        $json = file_get_contents($this->filePath);
        $this->data = json_decode($json);
    }

    public function event($event)
    {
        $sr = new SubscriptionRegistry($this->source);
        $sr->set($this->data);
        $sr->addEvent($event);
        file_put_contents($this->filePath, json_encode($sr));
    }
}
