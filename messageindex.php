<?php

session_start();
include 'database.php';
include 'src/User.php';
include 'src/Message.php';
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
                    <li class="active"><a href="messageindex.php">Wiadomości</a></li>
                    <li><a href="edit.php">Edytuj</a></li>
                    <li><a href="logowanie.php">Zaloguj</a></li>
                </ul>
            </div>
        </nav>
        <div class="container">

<?php
if (!isset($_SESSION['logged'])) {
   header('Location: logowanie.php'); 
}
$id = $_SESSION['logged'];
$message = Message::loadAllMessageByUserId($conn, $id);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {// loading message 
     {

        foreach ($message as $value) {
            $id2 = $value->getId();
            //$messageId = $value->getReciverId();
            //echo $messageId;
            $userName = User::loadUserById($conn, $value->getSenderId())->getUsername();


            echo '<table class="table">';
            echo '<tr>' . '<td>' . $value->getCreationDate() . '</td>' . '<td>' . $userName . '</td>' . '</tr>';
            echo '<tr>' . '<td>' . $value->getTitle() . '</td>' . '</tr>';
            echo '<tr>' . '<td>';
            if ($value->getReaded() == 0) {
                echo "Nowa";
            }
            echo '</td>' . '</tr>';
            echo "<a href='messageindex.php?id=$id2'>Odczytaj</a><br>";
            echo '<tr>' . '<td>';
            if (isset($_GET['id'])) {
                if ($value->getId() == $_GET['id']) {
                echo $value->getText();
                $value->setReaded(1);
                $value->saveToDB($conn);
                }
            }
            echo '</table>';
        }
    }
    
}
?>
</div>
</body>    
</html>

