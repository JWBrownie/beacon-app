<?php

class SubscriptionRegistry
{
    public string $topic;
    public array $events;
    public array $channels;

    public function __construct($topic)
    {
        $this->topic = $topic;
        $this->events = [];
        $this->channels = [];
    }

    public function set($data)
    {
        foreach ($data as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

    public function addEvent($event)
    {
        array_push($this->events, $event);
    }

    public function addChannel($channel)
    {
        array_push($this->channels, $channel);
    }

    public function hasChannel($channel)
    {
        foreach($this->channels as $storedChannel) {
            if($storedChannel == $channel) {
                return true;
            }
        }
        return false;
    }
}