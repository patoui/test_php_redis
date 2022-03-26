## ⚠️ WARNING ⚠️

**This repository is not meant as any kind of reference for production code.**

# Test PHP Redis Streams

Simple project to test redis streams

## Requirements

- [Docker](https://docs.docker.com/engine/install/)

### Running the application

Run the following

```
docker-compose up
```

It should be running on `localhost`

This application uses simple routing to trigger various redis stream actions:

```
/add = xAdd
/claim = xClaim
/read_group = xReadGroup
/ack = xAck
/len = xLen
/info = xInfo
```

### Example usage

#### Add message to a group

```php
use Patoui\TestPhpRedis\Consumer;
use Patoui\TestPhpRedis\Group;
use Patoui\TestPhpRedis\Messages\UserCreated;
use Patoui\TestPhpRedis\Stream;


$stream   = new Stream(redis(), 'mystream');
$group    = new Group($stream, 'mygroup');

$group->addMessage(new UserCreated(uniqid(), 'johndoe@email.com'));
```

#### Add message to a group

```php
use Patoui\TestPhpRedis\Consumer;
use Patoui\TestPhpRedis\Group;
use Patoui\TestPhpRedis\Messages\UserCreated;
use Patoui\TestPhpRedis\Stream;

$stream   = new Stream(redis(), 'users');
$group    = new Group($stream, 'created');

// Read messages from a consumer group
$consumer = new Consumer('notifications');
$messages = $consumer->readGroupMessages($group);

foreach ($messages as $id => $values) {
    /** @var UserCreated $user_created */
    foreach ($values as $user_created) {
        echo sprintf('User %s was created', $user->email) . PHP_EOL;
    }
    // acknowledge the message was processed
    $group->acknowledge($id);
}
```