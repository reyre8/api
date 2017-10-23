# Ensemble-challenge
###### @author: Reynaldo Rojas - reyre8@gmail.com
## Installation
### Database

Ensemble challenge requires a database installation (Mysql).
The schema can be found in **src/Schema/Ensemble.sql**

The steps to install the database as follows:

1. Create the database ensemble
   ```
   mysql> create database `ensemble`;
   ```
2. In command line, load the ensemble schema **src/Schema/Ensemble.sql**
   ```
   [user@server Schema] mysql ensemble < Ensemble.sql
   ```    
   
3. Create the database user
   ```
   mysql> create user `ensemble_user`;
   ```
4. Grant permissions on db to the user
   ```
   mysql> grant all on ensemble.* to 'ensemble_user'@'localhost' identified by 'ensemble_password';
   ```

if you wish to use a different configuration for the database, access the file **src/config.php**, and
update the following details:
   ```
    // Database configuration
    'db' => [
        'host' => 'localhost',
        'database' => 'ensemble',
        'user' => 'ensemble_user',
        'password' => 'ensemble_password'
    ] 
   ```

### Source code
Place the source code in your webserver, and install composer dependencies 

1. Run the installer
   ```
   [user@server ensemblechallenge] php installer
   ```
2. Install dependencies
   ```
   [user@server ensemblechallenge] php composer.phar install
```

### .htaccess configuration
An .htaccess file has got to be placed in the root of the project with the following content:

   ```
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule . index.php [QSA,L]
   ```
This is to allow the index to be rewritten for specific routing (i.e. /orders).

At this point the application should be ready to be used.

### Swagger docs
The .yaml file with the api documentation, can be found in the folder **apidocs/**. The file can be visualised on swagger online editor https://editor.swagger.io. Also swagger can be downloaded (https://github.com/swagger-api/swagger-ui) and installed in the folder **apidocs/**. Simply get the content of the **/dist** folder sitting on swagger and place it in the folder **apidocs/**

### Items to improve

- Customer and Item have got to be abstracted into new model classes with proper factories.
- Exception handler have got to be implemented as Business Logic Exception Handler.
- Validation to verify if customer exists (based on Email), so the same user is not created more than once.
- Implement unit test module.
- Implement financial operations.
- GLOBALS["config"] variable has got to be replaced with proper $config var, and injected where needed.
