### Webmasters Forge LTD 
Test registration & login app

To run app you need to have docker and docker-compose pre-installed.

#### Steps:
* docker-compose up
* docker exec -i $(docker-compose ps -q mysql) mysql -uwm_forge_user -p123456 wm_forge < users.sql