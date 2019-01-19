# Requirements

Docker

# Run

To start the project, just run:

```bash
docker-compose up
```

If you want to start containers in background(as a daemon), add the `-d` flag:

```bash
docker-compose up -d
```

If this is your first run, then after containers are up and ready, run the next command to setup/install the project dependencies:

```bash
docker-compose run --rm app ./build-deploy/first-run.sh
```

# Stop

You can stop containers by typing `Cmd + C` on Mac or `Ctrl + C` on Windows/Linux. 

If you started the project in background, then run:

```bash
docker-compose stop
```
# Force Running Project

There are many unexpected problems to stop running project. ex. Low version Ubuntu, openvpn connect problem.
```bash
sudo service docker restart
docker-compose down --rmi=local -v -f
```
This command will stop docker and restart, also remove all docker containers.
And Follow above steps again. Imoportant : 1.if you use vpn tool, disable it. 2. Maintain Memory 4GB: Run ElasticSearch docker Container. 

# Cleanup

If you want to cleanup your Docker instances for a fresh start, run:

```bash
docker-compose down --rmi=local -v
```

This command will stop and delete the containers, local images and volumes.

# Updating external images

If you want to get latest versions of your external images, run:

```bash
docker-compose pull
```

# Queues

Once your containers are started, you can start the queue listeners when needed.

### Mailing Queue

```bash
docker-compose run --rm app php artisan queue:work sqs_mail
```

### SMS Queue

```bash
docker-compose run --rm app php artisan queue:work sqs_sms
```
### Mac Set-up

```Docker Mac

    1. Install the docker in your mac

    2. After installing docker in your mac clone the repo (app) into your machine 

    3. Configure .env file and change mysql db_host to 127.0.0.1 and also change in docker.yaml file. mysql Environment to `MYSQL_HOST=127.0.0.1` and `app: aliases: - 127.0.0.1`

    4. Inside your app run `composer commands` to update all packages

    5. after that run make sure you have the righ permissions for that
            go to app->bulid-deploy 
                run `sudo chmod  +x first_run.sh`
                run `sudo chmod  +x run.sh`
            next go to app->bulid-deploy->image
                run `sudo chmod  +x run.sh`

    6. Now run  `docker-compose build` and  inside your app

    7. Start Laravel server to access app

    If you use sequel pro for mysql use these credentials database: app, host: 127.0.0.1, username: root
    Now you are able to access the app `http://127.0.0.1:8000/dashboard:login` url

    ```
#################################################
#         SWAGGER USING DOCUMENTATION           #
#################################################

# Generate beautiful RESTful Laravel API documentation with Swagger
- Run: php artisan list  TO check swagger already integrated in application:
If swagger already integrated, it will show these items in artisan list
l5-swagger
  l5-swagger:generate        Regenerate docs
  l5-swagger:publish         Publish config, views

- Create an endpoint and automate the documentation:
In controller class, above method of controller which you want to make API documentation, add below lines in comment:
/**
     * @OA\POST/GET/DELETE/PUT(
     *     path="/api/v1/{method_route_url}",
     *     summary="Example summary.",
     *     @OA\Parameter(
     *         list all parametters for method
     *         required=true,
     *         in="query"
     *     ),
     *     @OA\Response(
     *         write response format here
     *     )
     * )
     */


- RUN: php artisan l5-swagger:generate to generate document

- API document should be here: {app_url}/api/documentation