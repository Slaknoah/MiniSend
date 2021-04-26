# minisend
minisend - mailer for everyday business. 


## Installation
Before installing please confirm you have Docker and Node JS on your computer

```bash
# Clone project
$ git clone git@github.com:Slaknoah/MiniSend.git

# Go to project directory
$ cd Minisend

# Get submodules ( e.g laradock )
$ git submodule update --init --recursive
```

#### Installing the API
```bash
# Go to the api folder (.../Minisend/api)
$ cd api

# Set the .env files
$ cp .env.example .env
$ cp .env.testing.example .env.testing

# Lets also set the php worker config file at once
$ cp minisend-worker.conf.example laradock/php-worker/supervisord.d/minisend-worker.conf

# Go to the laradock folder (.../Minisend/api/laradock)
$ cd laradock

# Also set the .env file there
$ cp env-example .env

# Start docker services
$ docker-compose up -d nginx mysql mailhog rabbitmq php-worker 
$ docker-compose exec workspace bash
```
Enter the following in the opened bash terminal
```bash
# Install api dependencies 
$ composer install

# Migrate
$ php artisan migrate:fresh --seed

$ exit
```

[Documentation](https://documenter.getpostman.com/view/5709349/TzJycbCU) 

#### Frontend installation
```bash
# Go to the app folder  (.../Minisend/app)
$ cd app

# Set the .env file
$ cp env-example .env

# Install dependencies
$ npm install

# Start 
$ npm run dev
```
If all was done right you can now access your app frontend at [here](http://localhost:4000).

### Demo user
- email: user@gmail.com
- password: password

Once you login, generate your api tokens to access the mail api by including it in your authorization header 
- Send mail: POST - http://localhost/api/v1/emails

Happy Coding!
## License

[MIT](https://opensource.org/licenses/MIT)