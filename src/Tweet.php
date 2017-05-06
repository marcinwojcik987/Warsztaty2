<?php

class Tweet {

    private $id;
    private $userId;
    private $text;
    private $creationDate;
    
    function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->text = "";
        $this->creationDate = "";
    }

    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getText() {
        return $this->text;
    }

    function getDate() {
        return $this->creationDate;
    }    

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setDate($creationDate) {
        $this->creationDate = $creationDate;
    }
    
    static public function loadTweetById(PDO $conn, $id) {

        $stmt = $conn->prepare('SELECT * FROM Tweet WHERE id=:id ORDER BY creationDate DESC');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];

            return $loadedTweet;
        }

        return null;
    }
    
     static public function loadAllTweet(PDO $conn) {

        $sql = "SELECT * FROM Tweet ORDER BY creationDate DESC";
        $ret = [];

        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {

            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];

                $ret[] = $loadedTweet;
            }
        }

        return $ret;
    }
    
    static public function loadAllTweetByUserId(PDO $conn, $id) {

        $sql = "SELECT * FROM Tweet WHERE userId=$id ORDER BY creationDate DESC";
        $ret = [];

        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {

            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];

                $ret[] = $loadedTweet;
            }
        }

        return $ret;
    }
    
     public function saveToDB(PDO $conn) {

        if ($this->id == -1) {

            $stmt = $conn->prepare('INSERT INTO Tweet(userId, text, creationDate) VALUES (:userId, :text, now())');
            $result = $stmt->execute([ 'userId'=> $this->userId,'text'=> $this->text ]);

            if ($result !== false) {

                $this->id = $conn->lastInsertId();
                return true;

            }

        } else {

            $stmt = $conn->prepare(
                    'UPDATE Tweet SET userId=:userId, text=:text, creationDate=:creationDate WHERE id=:id');

            $result = $stmt->execute(['userId' => $this->userId,
                                      'text' => $this->text, 
                                      'creationDate' => $this->creationDate,
                                      'id' => $this->id]);

            if ($result === true) {

                return true;
            }
        }
    }

}
