<?php

require_once("AuthentifyDisplay.php");


class AuthentifyError {
    public $level = "danger";
    private $errorCode;
    private $provider = "";

    private $title;
    private $message;


    public $errors = array (
       'NK1' => array('No API Key', "There is no API key supplied for %s authentication", "warning"), 
       'IAPR' => array('Invalid Authentication', "The returned authentication service is not an Authentify provider", "danger"),
       'EAE' => array('Authorization Error', "%s could not authorize user", "danger"),
       'ANP' => array('No authenticated user', "Requested User Data before a succesful authentication flow", "danger"),
       'DEH' => array('Generic Debug Error', "PHP Error: %s", "danger"),
       'FUE' => array('Fatal Error', "Authentify, the system this website uses for authentication, has encountered a sever error. Teams have been dispatched to fix this as soon as possible. If you are the webmaster, enable debugging for more information", "danger")
    );
  


    public function __construct($errorCode, $provider = ""){
        $this->errorCode = $errorCode;
        $this->provider = $provider;
        $this->title = $this->errors[$errorCode][0];
        $this->message = sprintf($this->errors[$errorCode][1], $provider);
        $this->level = $this->errors[$errorCode][2];

    }

    public function printError(){
        ?>

        <div class='alert alert-<?php echo $this->level; ?>' role='alert'>
        <h4 class='alert-heading'>Authentify: <?php echo $this->title; ?></h4>
        <?php echo $this->message; ?>
        </div>

        <?php
    }


}



