## About StoryTime:
StoryTime is my next step in learning Vue as the frontend for a Laravel application. I's a responsive
designed simple forum with async elements. During development process I focused on: 
- security of data that is delivered from backend(by policies, gates and $hidden variable)
- not overengineer functionalities when it's not necessary(KISS-less is more)
- writing integration and unit tests before implementing given functionality

## Guide
- Larvel + Vue
- TDD,
- Fully containerized development environment
- Fully functional authorization(jetstream),
- Forced Eager Loading
- Using policies for models
- Using Resource API
- Using JS Promises for modal control
- Passing policy logic via resource API to the frontend
- Responsive Design
- Using slug for posts(SEO)

## Setup:
- Clone repo
- Duplicate .env.example as .env and adjust file permissions as executable
- Set ownership and permissions for public and storage directories (bash):
- "chown -R www-data:www-data storage public"
- "chmod -R 775 /var/www/html/storage"
- "chmod -R 755 /var/www/html/public"
- Build containers: "docker-compose up -d --build"
- Run: "docker-compose run --rm composer install"
- After installing dependencies run: "docker-compose run --rm artisan key:generate"
- Run migrations: "docker-compose run --rm artisan migrate"
- Followed by seeders: "docker-compose run --rm artisan db:seed"
- Next run: "docker-compose run --rm npm install" to install node dependencies
- Next run: "docker-compose run --rm npm run build" to build all assets
- **OR enable hrm: "docker-compose run -p 5173:5173 --rm npm run dev -- --host"**
- **It is recommended to place the project in the WSL subsystem optimized for Docker to avoid performance issues with HRM during development.**
- Login:test@example.com Password:11111111

## TODO:
- performance issue with node hrm container(1/1)
- Tests for controllers(1/2)
- CRUD for comments(4/4)
- CRUD for posts(1/4)
- policies(1/2)
- **For testing make sure to have php interpreter installed on your VM and set it as remote interpreter in IDE**