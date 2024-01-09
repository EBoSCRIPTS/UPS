
# Entrepreneurship management system

A simple Laravel project built for smaller companies to ease up the work-flow. Simple, yet 
powerful features. No need to pay large monthly sums for stuff you don't really need.




## Features

- Setup work related stuff - departments, employees.
- Register your company equipment
- Let employees log their worked hours, make absence requests etc.
- Mini KANBAN task  board - make your own boards, tasks
- One place where to read all the news, no need to spam emails!
- Find contacts quickly


## Setting up

To deploy this project:

```bash
  0. Make sure you've got PHP installed!
  1. Setup a server (if locally, eg. XAMP) where you can host Laravel projects
  2. Setup a MYSQL Database
  3. Clone the project from the repo.
  4. Open the project:
    - composer install
    - copy .env.example .env
  5. Setup your .env
  6. For Database - get the schema from the project and import it in your Database
  7. Run `php artisan serve` in your terminal(must be in project directory).
  8. To get the first user, do `php db:seed UserModelSeeder 
     - This will create your superadmin user: 
        login: admin@setup.com 
        password: password
     - Once logged in, navigate at the bottom left to your profile, change the password!!!

```


## Running Tests

To run tests, run the following command

```bash
  php artisan test
```

