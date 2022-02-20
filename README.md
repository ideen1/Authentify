# Authentify
A simple PHP Class handles third-party login and returns user information.
Authentify will handle everything from displaying the login buttons to handling the login flow on the backend.

## Configuration:
Open Authentify.php and include correct Client Keys and Secrets for each login provider you wish to use.

## Usage
On your Login page, include the following:
1. This creates a new authentify object
```php
$auth = new Authentify();
```
