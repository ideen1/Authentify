# Authentify
A simple PHP Class handles third-party login and returns user information.
Authentify will handle everything from displaying the login buttons to handling the login flow on the backend.
Every effort has been made to make the instructiosn clear, however there is an index.php provided that demos simple usage of Authentify

## Configuration:
Open Authentify.php and include correct Client Keys and Secrets for each login provider you wish to use.

## Usage
On your Login page, include the following:
1. Create a new authentify object
```php
$auth = new Authentify();
```

2. Enable providers you wish to use by supplying them as comma seperated values:
```php
$auth->enableProviders("google", "facebook", "github", "apple", "twitter");
```
3. The following lines will only run if a succesful authorization has occured and returned to the handler. Therefore you must place post-login code here.
```php
if($test->runAuth()){
  // Perform actions after login
}
```
