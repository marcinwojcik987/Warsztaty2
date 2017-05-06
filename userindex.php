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
                <li class="active"><a href="messageindex.php">Wiadomości</a></li>
                <li><a href="edit.php">Edytuj</a></li>
                <li><a href="logowanie.php">Zaloguj</a></li>
            </ul>
        </div>
    </nav>
<form class="form-inline" action="" method='POST'>    
    <h4>Wyślij wiadomość</h4>
    <div class="form-group">    
        <label>Tytuł wiadomości</label><br>     
        <input class="form-control" name='newTitle' type="text" /><br>
        <label>Treść wiadomości</label><br>  
        <textarea class="form-control" name='newMessage'></textarea>
    </div>
    <input class="btn btn-warning" type ='submit' value="Wyślij">
    <input name="id" value="<?= $_GET['userId'] ?>" hidden/>
    
</form>
</body>    
</html>

<?php
if (!isset($_SESSION['logged'])) {
   header('Location: logowanie.php'); 
}
$id = $_GET['userId'];
$tweets = (Tweet::loadAllTweetByUserId($conn, $id));

foreach ($tweets as $value) {  // show tweets
    $id = $value->getUserId();
    $id2 = $value->getId();
    $userName = User::loadUserById($conn, $id);
    $userNameLink = $userName->getUsername();
    echo '<table class="table">';
    echo '<tr>' . '<td>' . $value->getDate() . '</td>' . '<td>' . $userName->getUsername() . '</td>' . '</tr>';
    echo '<tr>' . '<td>' . $value->getText() . '</td>' . '</tr>';
    echo '</table>';
    echo "<a href='indexcomment.php?userName=$userNameLink&id=$id2'>Kometarze</a><br>"; // show link to comments
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {//sending message
    if (!isset($_POST['newMessage']) || !isset($_POST['id']) || !isset($_SESSION['logged'])) {
        echo "los problemos";
    } else {
        $newMessage = new Message();
        $newMessage->setTitle($_POST['newTitle']);
        $newMessage->setReciverId($_POST['id']);
        $newMessage->setSenderId($_SESSION['logged']);
        $newMessage->setText($_POST['newMessage']);
        $newMessage->saveToDB($conn);
	//var_dump($newMessage);	
    }
}