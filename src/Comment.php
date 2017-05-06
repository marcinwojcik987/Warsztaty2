<?php

class Comment {

    private $id;
    private $userId;
    private $postId;
    private $creationDate;
    private $text;

    function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->postId = "";
        $this->creationDate = "";
        $this->text = "";
    }

    function getId() {
        return $this->id;
    }

    function getUserId() {
        return $this->userId;
    }

    function getPostId() {
        return $this->postId;
    }

    function getCreationDate() {
        return $this->creationDate;
    }

    function getText() {
        return $this->text;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setPostId($postId) {
        $this->postId = $postId;
    }

    function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    function setText($text) {
        $this->text = $text;
    }

    static public function loadCommentById(PDO $conn, $id) {

        $stmt = $conn->prepare('SELECT * FROM Comment WHERE id=:id ORDER BY creationDate DESC');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->postId = $row['postId'];
            $loadedComment->creationDate = $row['creationDate'];
            $loadedComment->text = $row['text'];

            return $loadedComment;
        }

        return null;
    }

    static public function loadAllCommentsByPostId(PDO $conn, $id) {

        $ret = [];
        //$id = $id;
        $stmt = "SELECT * FROM Comment WHERE postId=$id ORDER BY creationDate DESC";
        $result = $conn->query($stmt);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {

                $loadedComment = new Comment();
                $loadedComment->id = $row['id'];
                $loadedComment->userId = $row['userId'];
                $loadedComment->postId = $row['postId'];
                $loadedComment->creationDate = $row['creationDate'];
                $loadedComment->text = $row['text'];

                $ret[] = $loadedComment;
            }
        }

        return $ret;
    }

    public function saveToDB(PDO $conn) {

        if ($this->id == -1) {

            $stmt = $conn->prepare('INSERT INTO Comment(userId, postId, creationDate, text) VALUES (:userId, :postId, now(), :text)');
            $result = $stmt->execute(['userId' => $this->userId, 'postId' => (int)$this->postId, 'text' => $this->text]);

            if ($result !== false) {

                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {

            $stmt = $conn->prepare(
                    'UPDATE Post SET userId=:userId, postId=:postId, creationDate=:creationDate, text=:text  WHERE id=:id');

            $result = $stmt->execute(['userId' => $this->userId,
                'postId' => $this->postId,
                'text' => $this->text,
                'creationDate' => $this->creationDate,
                'id' => $this->id]);

            if ($result === true) {

                return true;
            }
        }
    }

}
