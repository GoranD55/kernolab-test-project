# Kernolab-API
## First deploy

1. Clone repository
2. Create the `.env` file and copy the `env.example` file to it
3. Run `make init`
4. The API is available at http://localhost:8010/
5. All routes and those descriptions you can find in the file: openApi/swagger.yaml

#
## Useful commands:
### Change files permissions
`make permissions`

### Docker
Up docker containers: `make start` <br>
Down docker containers: `make down` <br>
Restart docker containers: `make restart`

### Database
Run migrations: `make migrate`<br>
Rollback migrations: `make rollback`

### Cache clear
`make cache-clear`

### Tests
Run project tests: `make test` <br>
Make a coverage report: `make test-coverage`


#
### Notes

In the implementation of this API, the transaction submit request was changed. The transaction_id field was added by
which we submit the transactions.

There are only two currencies in this API to work with transactions. Because in real Fintech projects we know which currencies we can use.
For the tests of this API I decided to leave two

I decided not to implement an endpoint to process all transactions, because in my opinion it's a bad practice

In my development, the main logic for working with transactions is carried out in services

Our transaction providers are stored in a database table. The logic for working with transaction details and these 
providers is written in the service, because there is no exact concept of how it should eventually work. 
And I think this is not the main task

In the test environment, I use the factory pattern to create a test model. But there are some problems with the Faker providers.
This issue prevents me from working with factories in tests. But for clarity, I created one factory

Currently, each background task work in sync mode. If you want to check the asynchronous execution of a task, do the following steps:
1. Change the QUEUE_CONNECTION value to the redis.
2. Run `make restart` to restart project
3. Run `docker compose exec php-fpm php artisan queue:work`
Then you can see some event states while transaction is completing
