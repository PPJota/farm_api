# farm_api
Api for an farm app


#Creating Database:
  Set the database path on .env at DATABASE_URL

  to create the database
  RUN: symfony console doctrine:database:create
  
  to make migration:
  RUN: 
    symfony console doctrine:migrations:diff
  and then
    symfony console doctrine:migrations:migrate
  to populate the DB
  run 
    symfony console hautelook:fixtures:load
  
