# Beacon Application
This set of files provides a stand-alone server for subscribing, publishing and retrieving events, the subscriptions can be made defining channels.

## How to run  
After cloning the repository in a terminal from the cloned directory follow the next steps to run the application:
1. Initialize the project run  
```composer update```
2. Run the server with the command  
```php -S localhost:8000 src/server.php```
3. Create a subscription to a topic provide a channel url for example.  
```curl -X POST -d '{ "url": "http://localhost:8000/events"}' http://localhost:8000/subscribe/alarms```
4. You can publish an event to the given topic for example.  
```curl -X POST -H "Content-type: application/json" -d '{"message": "Alarm 56 - Armed"}' http://localhost:8000/publish/alarms```
5. To retrieve the events from subscribed from a given channel you can request a Get to the channel.  
```curl http://localhost:8000/event```

You can repeat step 3 and 4 as many times has needed to express multiple channels and events.

Thank you, for the interest in this content. 
Cheers!