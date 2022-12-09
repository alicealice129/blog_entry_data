# Instructions

## About this website
- Fetch the blog entry data from FC2BLOG(Japanese blog website) and display some information including data, URL, title, description
- Search the data from data, URL, username, server number, entry number

## Development Environment
- Langguage: PHP, JavaScript, HTML, CSS
- Framework: Smarty, Bootstrap5
- Database: MySQL
- Others: Docker, AWS EC2

## Set up local env
### install last version of docker 
Go in the root folder (blog_data_app folder)
``` docker-compose up -d ```　　

### cron to input the file https://blog.fc2.com/newentry.rdf to the website database
``` 
crontab -e 　　
*/5 * * * * docker exec php-apache php fill_db.php 
```

or if php and mysqli are installed on the server,  
``` 
crontab -e 
*/5 * * * * php <absolute path to php/src/>fill_db.php 
```

### Host the website locally with docker
Make sure the configuration of the database is correct in db_config.php
``` 
    $config=array(
        "servername" => "db:____",
        "username" => "",
        "password" => "",
        "dbname" => ""
    );
```

Make sure the docker env is up:
``` docker-compose up -d ```

* go to the webpage
http://localhost/smarty_app/index.php

### Host the website locally without docker and php with mysqli installed on the server
Make sure the configuration of the database is correct in db_config.php
``` 
    $config=array(
        "servername" => "localhost:____",
        "username" => "",
        "password" => "",
        "dbname" => ""
    );
```

``` 
cd php/src 
``` 
run the command:
``` docker exec -ti php-apache php -S localhost:8000``` 
- go to the webpage
http://localhost:8000/smarty_app/index.php

## Set up the server on AWS EC2
### Install docker docker-compose
- create a vm instance aws linux allowing ssh and http from aws GUI
- connect to the instance via ssh using the ssh key generated when setting up the EC2 VM: 
  ``` ssh -i my_key.pem ec2-user@EC2-SERVER-IP```
- update yum repo
  ``` sudo yum update -y```
- install docker
  ``` sudo amazon-linux-extras install docker -y``` 
- start docker:
  ``` sudo service docker start```
- remove the need to use sudo when using docker
  ``` sudo usermod -a -G docker ec2-user```
- exit from the server and ssh back in:
- install docker-compose
``` $ sudo curl -L https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose```
- setting permissions
```sudo chmod +x /usr/local/bin/docker-compose```
- auto-start docker when server reboot
```sudo chkconfig docker on```

### Get the website code from Git
``` 
sudo yum install git -y 
git clone REPO_ADDRESS 
```

### File permissions
``` 
cd php/src 
chmod 664 *.php
cd smarty_app 
chmod 664 *.php
chmod 664 models/* 
chmod 775 templates
chmod 777 templates_c 
```
- vendor folder is restricted in .htaccess

### Build the docker and launch the website, change the file permission
``` 
cd blog_data_app 
docker-compose up -d 
```

### Set up the cron on the server to fill the database
``` 
crontab -e 
*/5 * * * * docker exec php-apache php fill_db.php 
```

### Update the website 
``` 
cd blog_data_app  
docker-compose down 
git pull 
docker-compose up -d 
```

### Open the website
http://[SERVER_NAME]/smarty_app/page.php
