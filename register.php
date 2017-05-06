<?php

session_start();
include 'database.php';
include 'src/User.php';
include 'src/Message.php';
include 'src/Tweet.php';
?>

<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>    
    
<body>    
<form class="form-inline" action="" method='POST'>
    <h4>Zarejestruj się: </h4>
    <div class="form-group">
    <label>Imię</label>    
        <input  class="form-control" name='name' type="text" /><br>
    <label>Email:</label>   
        <input class="form-control" name='mail' type="text" /><br>
    <label>Hasło:</label>
        <input class="form-control" name='newPass' type="text"/><br>
    </div>    
    <input class="btn btn-warning" type ='submit' value="Zarejestruj">   
</form>
</body>    
</html>

<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {//change comment
    if (!isset($_POST['name']) || !isset($_POST['mail'])|| !isset($_POST['newPass'])) {
        echo "los problemos";
    } else {
                     
        $newUser = new User();        
              
        $newUser->setUsername($_POST['name']);
        $newUser->setEmail($_POST['mail']);
        $newUser->setPassword($_POST['newPass']);       
        $newUser->saveToDB($conn);
   	
        header('Location: logowanie.php');
    }
    
}

