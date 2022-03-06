<?php
/*  
    Authentify PHP Class handles third-party login
    and returns user information. Follow instructions
    in README for deployment and implementation guide.
    Created by Ideen B. in 2022

    **This software is provided under the Apache License**.

*/
require_once("lib/AuthentifyGlobal.php");
require_once("lib/AuthentifyDisplay.php");
require_once("lib/AuthentifyError.php");

class Authentify {

    // ***EDIT THE FOLLOWING LINES TO CONFIGURE***
    // Refer to README for instructions

    // Input keys for services you want to use. 
    

    private $keys = [
        // Google Sign-in:
        "google" => 
            [
            "CLIENT_KEY" => "757078276056-8q82tlubgag6ds518dq5ommo6q5ksf6a.apps.googleusercontent.com", 
            "SECRET" => "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"
            ],
        
        // Facebook Sign-in:
        "facebook" => [
            "CLIENT_KEY" => "1340663753116498", 
            "SECRET" => "3ed9b578aef43d52046cae49e4068c0a"
            ],

        "twitter" => [
            "CLIENT_KEY" => "h", 
            "SECRET" => ""
            ],

        "apple" => [
            "CLIENT_KEY" => "h", 
            "SECRET" => ""
            ],

        "github" => [
            "CLIENT_KEY" => "h", 
            "SECRET" => ""
            ]
    ];

    // Manually force provider to be enabled by default
    private $enabled = [
        "google" => 0,
        "facebook" => 0,
        "twitter" => 0,
        "apple" => 0,
        "github" => 0
    ];

    //private $operating_address = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    public $state;
    public $user;
    private $authenticated = false;
    private $authorizers = [];


    public function getUID(){
        if($this->isAuth()){
            return $this->user["UID"];
        }
    }
    public function getFirstName(){
        if($this->isAuth()){
            return $this->user["FirstName"];
        }
    }
    public function getLastName(){
        if($this->isAuth()){
            return $this->user["LastName"];
        }
    }
    public function getUserEmail(){
        if($this->isAuth()){
            return $this->user["Email"];
        }
    }
    public function getEmailVerified(){
        if($this->isAuth()){
            return $this->user["email_verified"];
        }
    }
    public function getUserPhoto(){
        if($this->isAuth()){
            return $this->user["ProfilePhoto"];
        }
    }

    public function enableProviders(){
        $services = func_get_args();
        foreach ($services as $service)
            $this->enabled[$service] = 1;
            
    }
    


    // Display Authentify Buttons
    public function displayForm(){
        foreach($this->keys as $service => $api){
            if($this->enabled[$service] == 1){
                if ($api["CLIENT_KEY"] == ""){
                    $error = new AuthentifyError("NK1", $service);
                    $error->printError();
                } else {
                    switch ($service):
                        case "google": require_once("static/google-static.php"); break;
                        case "facebook": require_once("static/facebook-static.php"); break;
                        case "apple": require_once("static/apple-static.php"); break;
                        case "github": require_once("static/github-static.php"); break;
                        case "twitter": require_once("static/twitter-static.php"); break;
                    endswitch;
                }
            }
            
        }
        
    }

    // Verify if Authentify Object has authenticated a user
    private function isAuth(){
        if ($this->authenticated == true){
            return true;
        } else{
            $error = new AuthentifyError("ANP");
            $error->printError();
            return false;
        }
    }   

    
    public function runAuth(){
        if (isset($_GET['AUTHENTIFY_AUTH_RECIEVED'])){
            
            switch ($_GET['AUTHENTIFY_AUTH_RECIEVED']):
                case "google": return $this->runGoogle(); break;
                case "facebook": return $this->runFacebook(); break;
                case "twitter": $this->runTwitter; break;
                case "apple": $this->runApple; break;
                case "github": $this->runGitHub; break;
                default: 
                $error = new AuthentifyError("IAPR");
                $error->printError();
                return false;
                break;
            endswitch;
        } else {
            $this->state = "NORMAL - runAuth was attempted with no AUTHENTIFY_AUTH_RECIEVED flag but was handled";
            return false;
        }
    }

    private function returnAuthorizationInformation($UID, $fname, $lname, $email, $picture, $emailVerified){
        $this->authenticated = true;
        $this->user["UID"] = $UID;
        $this->user["FirstName"] = $fname;
        $this->user["LastName"] = $lname;
        $this->user["Email"] = $email;
        $this->user["Email_verified"] = $emailVerified;
        $this->user["ProfilePhoto"] = $picture;

    }

    private function runFacebook(){
        require 'lib/dependencies/facebook-auth/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => $this->keys['facebook']['CLIENT_KEY'],
            'app_secret' => $this->keys['facebook']['SECRET'],
            'default_graph_version' => 'v2.5',
           ]);
           $helper = $fb->getRedirectLoginHelper();
           if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }

           try {
            $accessToken = $helper->getAccessToken();
            $fb->setDefaultAccessToken($accessToken);
            $response = $fb->get('/me?locale=en_US&fields=id,first_name,last_name,email,picture');
            $userNode = $response->getGraphUser();
            $this->returnAuthorizationInformation($userNode->getField('id'), $userNode->getField('first_name'), $userNode->getField('last_name'), $userNode->getField('email'), $userNode->getField('picture')['url'], true);
            return true;
            } catch(Facebook\Exceptions\facebookResponseException $e) {
            // When Graph returns an error
            $error = new AuthentifyError("EAE", "Facebook");
            $this->state = "ERROR: Graph returned an error: " . $e->getMessage();;
            return false;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $error = new AuthentifyError("EAE", "Facebook");
            $this->state = "ERROR: Facebook SDK returned an error: " . $e->getMessage();;
            return false;
 
            }
    }

    private function runGoogle(){
        require_once ('lib/dependencies/google-auth/vendor/autoload.php');

        //GET GOOGLE ID TOKEN FROM GOOGLE
        $url = 'https://www.googleapis.com/oauth2/v4/token/';
        $data = array('code' => $_GET['code'], 'client_id' => $this->keys['google']['CLIENT_KEY'], 'client_secret' => $this->keys['google']['SECRET'], "redirect_uri" => "https://312.ideen.ca/apps/Authentify/index.php?AUTHENTIFY_AUTH_RECIEVED=google", "grant_type" => "authorization_code");

        // use key 'http' even if you send the request to https://...
        $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded",
            'protocol_version' => 1.1,
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
        );

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result, true);

        if ($result === FALSE) { 
            $error = new AuthentifyError("EAE", "Google");
            $this->state = "ERROR: " . $result;
            return false;
        }
        // Get User Info from Google by Token
        $client = new Google_Client(['client_id' => $this->keys['google']['CLIENT_KEY']]); 
        $payload = $client->verifyIdToken($result['id_token']);
        if ($payload) {
            $this->returnAuthorizationInformation($payload['sub'],  $payload['given_name'], $payload['family_name'], $payload['email'], $payload['picture'], $payload['email_verified']);
            
            return true;
        } else{
            $error = new AuthentifyError("EAE", "Google");
            $state = "ERROR: " . json_decode($result, true);
            return false;
        }

}



}


class noAuthenticatedUser extends Exception{
    
    public function __construct(){
        $error = new AuthentifyError("ANP");
        $error->printError();
    }

}
class noKeyforAuthProvider extends Exception {

}

class genericError extends Exception{
    public function __construct(){
        $error = new AuthentifyError("G");
        $error->printError();
    }
}