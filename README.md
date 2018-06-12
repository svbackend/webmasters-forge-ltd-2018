### Webmasters Forge LTD 
Test registration & login app (2018 year edition)

To run app you need to have docker and docker-compose pre-installed.

#### Steps:
* docker-compose up
* docker exec -i $(docker-compose ps -q mysql) mysql -uwm_forge_user -p123456 wm_forge < users.sql

Older version of this app can be found at https://github.com/svbackend/webmasters-forge-ltd (2016 year) and online (also old version but frontend pretty close to current one) here: http://forge.sv-backend.xyz/