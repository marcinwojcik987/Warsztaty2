<?php
session_start();
include 'database.php';
include 'src/User.php';
include 'src/Tweet.php';
//var_dump($_SESSION);
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
      
      <li class="active"><a href="index.php">Strona główna</a></li>
      <li><a href="messageindex.php">Wiadomości</a></li>
      <li><a href="edit.php">Edytuj</a></li>
      <li><a href="logowanie.php">Zaloguj</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<form class="form-inline" action="index.php" method='POST'>
    <textarea class="form-control" name='newTweets'></textarea>
    <input class="btn btn-warning" type="submit" type="submit" value="Prześlij tweeta"/>
</form>


<?php
if (!isset($_SESSION['logged'])) {
   header('Location: logowanie.php'); 
}
$tweets = (Tweet::loadAllTweet($conn));

foreach ($tweets as $value) {
    $id = $value->getUserId();
    $id2 = $value->getId();
    $userName = User::loadUserById($conn, $id);
    $userNameLink = $userName->getUsername();
    echo '<table class="table">';
    echo '<tr>' . '<td>' . $value->getDate() . '</td>' . '<td>' . '<a href =\'userindex.php?userId=' . $id . '\'>' . $userName->getUsername() . '</a> </td>' . '</tr>';
    echo '<tr>' . '<td colspan=2>' . $value->getText() . '</td>' . '</tr>';
    echo '</table>';
    echo "<a href='indexcomment.php?userName=$userNameLink&id=$id2'>Kometarze</a><br>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['newTweets'])) {

        $tweet = trim($_POST['newTweets']);
        
        $newTweet = new Tweet();
        $newTweet->setText($tweet);
        $newTweet->setUserId($_SESSION['logged']);
        $newTweet->saveToDB($conn);
    }
    header('Location: index.php');
    
}
?>
</div>
</body>    
</html>

