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

If you'd like to test messaging (Redis Streams) be sure to run one of the consumer scripts found in the `scripts` directory, see the section below for more details on their behaviour.

This application uses simple routing to trigger various actions against a bank account:

```
GET /new/store = create a new account
GET /new/update?uuid=[account_uuid]&a=[signed integer of amount to deposit or withdraw] = update an account balance
GET /new/show?uuid=[account_uuid] = show an account's balance
```

### Example usage

To see how events are added to an aggregate root (Account), see `NewController.php`

### Additional test scripts

There are 3 scripts found in the `scripts` directory:
- `multi_consumer.php` to demonstrate how an application could setup multi consumer (all consumers get all messages)
- `consumer.php` to demonstrate how an application could setup consumers (consumers only process messages once)
- `producer.php` to demonstrate how an application can utilize aggregates to produce events/messages