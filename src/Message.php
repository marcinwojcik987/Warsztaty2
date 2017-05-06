<?php

class Message {

    private $id;
    private $senderId;
    private $reciverId;
    private $creationDate;
    private $text;
    private $title;
    private $readed;

    function __construct() {
        $this->id = -1;
        $this->senderId = "";
        $this->reciverId = "";
        $this->creationDate = "";
        $this->text = "";
        $this->title = "";
        $this->readed = "";
    }
    function getTitle() {
        return $this->title;
    }

    function getReaded() {
        return $this->readed;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setReaded($readed) {
        $this->readed = $readed;
    }

        function getId() {
        return $this->id;
    }

    function getReciverId() {
        return $this->reciverId;
    }

    function getSenderId() {
        return $this->senderId;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getText() {
        return $this->text;
    }

    function setSenderId($senderId) {
        $this->senderId = $senderId;
    }

    function setReciverId($reciverId) {
        $this->reciverId = $reciverId;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function setText($text) {
        $this->text = $text;
    }
   

    static public function loadMessageById(PDO $conn, $id) {

        $stmt = $conn->prepare('SELECT * FROM Message WHERE id=:id ORDER BY creationDate DESC');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $loadedMessage = new Message();
            $loadedMessage->id = $row['id'];
            $loadedMessage->senderId = $row['senderId'];
            $loadedMessage->reciverId = $row['reciverId'];
            $loadedMessage->creationDate = $row['creationDate'];
            $loadedMessage->text = $row['text'];
            $loadedMessage->title = $row['title'];
            $loadedMessage->readed = $row['readed'];

            return $loadedMessage;
        }

        return null;
    }

    static public function loadAllMessageByUserId(PDO $conn, $id) {

        $ret = [];
        //$id = $id;
        $stmt = "SELECT * FROM Message WHERE reciverId=$id ORDER BY creationDate DESC";
        $result = $conn->query($stmt);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {

                $loadedMessage = new Message();
                $loadedMessage->id = $row['id'];
                $loadedMessage->reciverId = $row['reciverId'];
                $loadedMessage->senderId = $row['senderId'];
                $loadedMessage->creationDate = $row['creationDate'];
                $loadedMessage->text = $row['text'];
                $loadedMessage->title = $row['title'];
                $loadedMessage->readed = $row['readed'];

                $ret[] = $loadedMessage;
            }
        }

        return $ret;
    }

    public function saveToDB(PDO $conn) {

        if ($this->id == -1) {

            $stmt = $conn->prepare('INSERT INTO Message(senderId, reciverId, creationDate, text, title) VALUES (:senderId, :reciverId, now(), :text, :title)');
            $result = $stmt->execute(['senderId' => $this->senderId, 'reciverId' => (int)$this->reciverId, 'text' => $this->text, 'title' => $this->title]);

            if ($result !== false) {

                $this->id = $conn->lastInsertId();
                return true;
            }
        } 
        
    
    else {

            $stmt = $conn->prepare(
                    'UPDATE Message SET readed=:readed WHERE id=:id');

            $result = $stmt->execute(['readed' => $this->readed,                                      
                                      'id' => $this->id]);

            if ($result === true) {

                return true;
            }
        }
    }

}