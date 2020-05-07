<?php
require 'SubscriptionRegistry.php';

class Subscriber
{
    protected string $filePath;
    protected string $channel;
    public string $source;
    public object $data;

    public function __construct($source, $channel)
    {
        $this->channel = $channel;
        $this->source = explode('.', $source)[0];
        $this->filePath = './src/dataset/' . $source;
        $this->initialize();
    }

    protected function initialize()
    {
        if (!file_exists($this->filePath)) {
            $default = $this->initialObjectStructure();
            file_put_contents($this->filePath, json_encode($default));
        }
        $json = file_get_contents($this->filePath);
        $this->data = json_decode($json);
    }

    public function getSubscriptions()
    {
        http_response_code(200);
        header('Content-type: application/json');
        $output = json_encode($this->data);
        echo $output . PHP_EOL;
    }

    public function initialObjectStructure(): object
    {
        $sr = new SubscriptionRegistry($this->source);
        $sr->addChannel($this->channel);
        return $sr;
    }
}