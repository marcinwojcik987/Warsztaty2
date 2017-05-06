<?php

session_start();
unset($_SESSION['logged']);
include 'database.php';
include 'src/User.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['password']) || !isset($_POST['mail'])) {
     echo "nie dziala";   
    } else {
        try {
            $logUser = User::loadUserByMail($conn, $_POST['mail']);
            if ($logUser != null) {
                if (1==1) {
                    $_SESSION['logged'] = $logUser->getId();
                    echo $logUser->getId();
                    //echo 'zalogowany';
                   header('Location: index.php');
                } else {
                    //header("Location: ../login.php?errMess=pass");
                }
            } else {
                echo "Zły login lub hasło";
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            
        }
    }
} else {
   
}
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
                <li><a href="edit.php">Edytuj</a></li>
                <li class="active"><a href="logowanie.php">Zaloguj</a></li>
            </ul>
        </div>
    </nav>
        <form class="form-inline" action="logowanie.php" method="post">
            <label>Email</label><br>
            <input class="form-control" name="mail" required="required" placeholder="Email" type="text"> <br>
            <label>Hasło</label><br>
            <input class="form-control" name="password" required="required" placeholder="Hasło" type="password"><br>
            <input class="btn btn-warning"name="submit" value="Zaloguj się" type="submit">
        </form>
         <p>
                    </p>
        <p> 
            Nie masz jeszcze konta?? <a href="register.php"> Zarejestruj się!</a>
        </p>
</body>    
</html>       