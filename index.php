<?php

require_once("Authentify.php");


$test = new Authentify();
$test->enableProviders("google", "facebook", "github", "apple", "twitter");

$message = "";
if($test->runAuth()){
    $id = $test->getUID();
    $fname = $test->getFirstName();
    $lname = $test->getLastName();
    $email = $test->getUserEmail();
    $img = $test->getUserPhoto();
    $message = "<br>Hey, <b> $fname </b>! Your last name is <b> $lname </b> and your email is: <b>$email</b> ID: $id <br> <img src='$img'>";
    
} 


?>

<!DOCTYPE html>   
<html>   
<head>  
<meta name="viewport" content="width=device-width, initial-scale=1">  
<title> Login Page </title>  
<style>   
Body {  
  font-family: Calibri, Helvetica, sans-serif;  
 
}  
button {   
       background-color: #4CAF50;   
       width: 100%;  
        color: orange;   
        padding: 15px;   
        margin: 10px 0px;   
        border: none;   
        cursor: pointer;   
         }   
 form {   
        border: 3px solid #f1f1f1;   
    }   
 input[type=text], input[type=password] {   
        width: 100%;   
        margin: 8px 0;  
        padding: 12px 20px;   
        display: inline-block;   
        border: 2px solid green;   
        box-sizing: border-box;   
    }  
 button:hover {   
        opacity: 0.7;   
    }   
  .cancelbtn {   
        width: auto;   
        padding: 10px 18px;  
        margin: 10px 5px;  
    }   
        
     
 .container {   
        padding: 25px;   
        width: 50%;
        background-color: lightblue;  
    }   
</style>   
</head>    
<body>    
    <center> <h1> Authentify Login Demo </h1> </center>   
    <form>  
        <div class="container">   
        <?php $test->displayForm(); ?>
        <!--
            <label>Username : </label>   
            <input type="text" placeholder="Enter Username" name="username" required>  
            <label>Password : </label>   
            <input type="password" placeholder="Enter Password" name="password" required>  
            <button type="submit">Login</button>   
            <input type="checkbox" checked="checked"> Remember me   
            <button type="button" class="cancelbtn"> Cancel</button>   
            Forgot <a href="#"> password? </a>   -->
            <?php echo $message;?>
        </div>   
    </form>     
</body>     
</html>  