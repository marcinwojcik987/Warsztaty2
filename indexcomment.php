<?php
session_start();
include 'database.php';
include 'src/User.php';
include 'src/Tweet.php';
include 'src/Comment.php';
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

                <li><a class="active"<a href="index.php">Strona główna</a></li>
                <li><a href="messageindex.php">Wiadomości</a></li>
                <li><a href="edit.php">Edytuj</a></li>
                <li><a href="logowanie.php">Zaloguj</a></li>
            </ul>
        </div>
    </nav>     
    <div class="container">
<div class="bg-4 padding border-dashed-orange">
    <form action="" method="post">
        <h4>Komentarz</h4>  
        <input type="text" name="newComment" placeholder="komentarz"/>
        <input class="btn btn-warning" type="submit" type="submit" value="Skomentuj!"/>
        <input name="id" value="<?= $_GET['id'] ?>" hidden/>        
    </form>
</div>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {//adding comment
    if (!isset($_POST['newComment']) || !isset($_POST['id']) || !isset($_SESSION['logged'])) {
        echo "los problemos";
    } else {
        $newComment = new Comment();
        $newComment->setPostId($_POST['id']);
        $newComment->setUserId($_SESSION['logged']);
        $newComment->setText($_POST['newComment']);
        $newComment->saveToDB($conn);
	//var_dump($newComment);	
         echo "<div class='bg-2 padding border-dashed-orange margin'>";
        $curComments = Comment::loadAllCommentsByPostId($conn, $_POST['id']);
	//var_dump($_SESSION['logged']);
	//var_dump($curComments);
        $tweet = Tweet::loadTweetById($conn, $_POST['id']);
        echo "<strong>" . $tweet->getText() . "</strong>";
        foreach ($curComments as $row) {
        $author = User::loadUserById($conn, $row->getUserId()); 
        	
            echo $row->getText() . "<br> <i>" . "<a href='index.php?wantGet=profile&id={$row->getUserId()}'>{$author->getUsername()}</a><br>" . "</i><br><br>";
        }
        echo "</div>";
    }
    
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {// loading comments 
    if (!isset($_GET['id'])) {
        echo 'nie wiem o ktory post chodzi';
    } else {

        echo "<div class='bg-2 padding border-dashed-orange margin'>";
        $curComments = Comment::loadAllCommentsByPostId($conn, $_GET['id']);
	//var_dump($_SESSION['logged']);
	//var_dump($curComments);
        $tweet = Tweet::loadTweetById($conn, $_GET['id']);
        echo "<mark>" . $tweet->getText() . "</mark>" . "<br><br><br>";
        foreach ($curComments as $row) {
        $author = User::loadUserById($conn, $row->getUserId()); 
        	
            echo $row->getText() . "<br> <i>" . "<a href='index.php?wantGet=profile&id={$row->getUserId()}'>{$author->getUsername()}</a><br>" . "</i><br><br>";
        }
        echo "</div>";
    }
}

?>
 </div>
</body>    
</html>
