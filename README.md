# Authentify
A simple PHP Class that handles third-party login and returns user information.
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
3. The following lines will only run if a succesful authorization has occured. Therefore you must place post-login code here.
```php
if($auth->runAuth()){
  // Perform actions after login (See below for returned parameters)
}
```
4. Place this line where you want the login buttons to appear.
```php
$auth->displayForm();
```
## Returned Parameters:
The following parameters can be accessed after runAuth() has been checked. Attempting to use them before a login will throw an error.

```php
$auth->getUID(); // Returns unique UserID provided by authentication provider
$auth->getFirstName(); // Returns first name provided by authentication provider
$auth->getLastName(); // Returns last name provided by authentication provider
$auth->getUserEmail(); // Returns email provided by authentication provider
$auth->getUserPhoto(); // Returns user profile photo provided by authentication provider
$auth->getEmailVerified(); // Returns boolean depending on if the email is verified

```
Typically these values can then be processed by your database just as normal login values
