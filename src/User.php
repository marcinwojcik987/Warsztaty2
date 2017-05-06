<?php

class User {

    private $id;
    private $username;
    private $hashPass;
    private $email;

    public function __construct() {

        $this->id = -1;
        $this->username ="";
        $this->email ="";
        $this->hashPass ="";

    }
    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getHashPass() {
        return $this->hashPass;
    }

    function getEmail() {
        return $this->email;
    }
    
    function setUsername($username) {
        $this->username = $username;
    }

    function setHashPass($hashPass) {
        $this->hashPass = $hashPass;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($newPass) {

        $newHashedPassword = password_hash($newPass, PASSWORD_BCRYPT);
        $this->hashPass = $newHashedPassword;
    }
    ///Zapisywanie nowego obiektu(uzytkownika) do bazy danych i jego modyfikacja
    public function saveToDB(PDO $conn) {

        if ($this->id == -1) {

            $stmt = $conn->prepare('INSERT INTO Users(username, email, hash_pass) VALUES (:username, :email, :pass)');
            $result = $stmt->execute([ 'username'=> $this->username,'email'=> $this->email,'pass'=> $this->hashPass ]);

            if ($result !== false) {

                $this->id = $conn->lastInsertId();
                return true;

            }

        } else {

            $stmt = $conn->prepare(
                    'UPDATE Users SET username=:username, email=:email, hash_pass=:hash_pass WHERE id=:id');

            $result = $stmt->execute(['username' => $this->username,
                                      'email' => $this->email, 
                                      'hash_pass' => $this->hashPass,
                                      'id' => $this->id]);

            if ($result === true) {

                return true;
            }
        }
    }
    ///Wczytywanie obiektu z bazy danych
    static public function loadUserById(PDO $conn, $id) {

        $stmt = $conn->prepare('SELECT * FROM Users WHERE id=:id');
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];

            return $loadedUser;
        }

        return null;
    }
    
    ///Wczytywanie wielu obiektu z bazy danych
    static public function loadAllUsers(PDO $conn) {

        $sql = "SELECT * FROM Users";
        $ret = [];

        $result = $conn->query($sql);
        if ($result !== false && $result->rowCount() != 0) {
            foreach ($result as $row) {

                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashPass = $row['hash_pass'];
                $loadedUser->email = $row['email'];

                $ret[] = $loadedUser;
            }
        }

        return $ret;
    }
    
    ///Usunięcie obiektu
    public function delete(PDO $conn) {

        if ($this->id != -1) {
            $stmt = $conn->prepare('DELETE FROM Users WHERE id=:id');
            $result = $stmt->execute(['id' => $this->id]);

            if ($result === true) {
                $this->id = -1;
                
                return true;
            }

            return false;
        }

        return true;
    }
    
    static public function loadUserByMail(PDO $conn, $mail) {
        $stmt = $conn->prepare('SELECT * FROM Users WHERE email=:mail');
        $result = $stmt->execute(['mail' => $mail]);
        if ($result === true && $stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashPass = $row['hash_pass'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

}
