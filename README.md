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
