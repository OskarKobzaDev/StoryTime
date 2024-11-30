## About StoryTime:
StoryTime is my next step in learning Vue as the frontend for a Laravel application.

- Larvel + Vue
- TDD,
- Fully contenerized development enviroment
- Fully functional authorization,
- Forced Eager Loading
- Using policies for models
- Using Resource API
- Passing policy logic via resource API to the frontend

## Setup:
- Clone repo
- Duplicate .env.example as .env
- Build containers
- Run: "docker-compose run --rm composer install"
- After installing dependencies run: "docker-compose run --rm artisan key:generate"
- Run migrations: "docker-compose run --rm artisan migrate"
- Followed by seeders: "docker-compose run --rm artisan db:seed"
- Next run: "docker-compose run --rm npm install" to install node dependencies
- Next run "docker-compose run --rm npm run build" to build all assets
- **OR enable hrm(really low perwormance atm): "docker-compose run -p 5173:5173 --rm npm run dev -- --host"**
- **It is strongly recommended to use node on host machine for hrm**
- Login:test@example.com Password:11111111
  


## TODO:
- performace issue with node container(hrm)
- CRUD for comments(2/4)
- CRUD for posts(0/4)
- policies
