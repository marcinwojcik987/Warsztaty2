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
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Tweeter</a>
            </div>
            <ul class="nav navbar-nav">

                <li><a href="index.php">Strona główna</a></li>
                <li><a href="messageindex.php">Wiadomości</a></li>
                <li class="active"><a href="edit.php">Edytuj</a></li>
                <li><a href="logowanie.php">Zaloguj</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">    
<form class="form-inline" action="" method='POST'>
    <label>Imię</label><br>
        <input class="form-control" name='name' type="text"/><br>        
    <label>Email</label><br>     
        <input class="form-control" name='mail' type="text" /><br>
     <label>Nowe hasło</label><br>   
        <input class="form-control" name='newPass' type="text"/><br>
     <label>Stare hasło</label><br>   
        <input class="form-control" name='oldPass' type="text"/><br><br> 
    
    <input class="btn btn-warning" type ='submit' value="edytuj">   
</form>
</div>

<?php
if (!isset($_SESSION['logged'])) {
   header('Location: logowanie.php'); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['name']) || !isset($_POST['mail'])|| !isset($_POST['newPass'])|| !isset($_POST['oldPass']) || !isset($_SESSION['logged'])) {
        echo "los problemos";
    } else {
        
        $id = $_SESSION['logged'];
        $newUser = User::loadUserById($conn, $id);
        
        if (password_verify($_POST['oldPass'], $newUser->getHashPass())) {       
        $newUser->setUsername($_POST['name']);
        $newUser->setEmail($_POST['mail']);
        $newUser->setPassword($_POST['newPass']);       
        $newUser->saveToDB($conn);
        }
        else {
            echo 'niedziala';
        }
	
    }
}
?>

</body>    
</html>
