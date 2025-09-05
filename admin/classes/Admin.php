<?php
class Admin {
    private $db;
    public function __construct(){ $this->db = new Database; }

    public function login($username, $password){
        $this->db->query('SELECT * FROM admins WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();

        if($row && password_verify($password, $row->password)){
            return $row;
        } else {
            return false;
        }
    }

    public function findByUsername($username) {
        $this->db->query('SELECT id FROM admins WHERE username = :username');
        $this->db->bind(':username', $username);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function register($username, $password) {
        $this->db->query('INSERT INTO admins (username, password) VALUES (:username, :password)');
        $this->db->bind(':username', $username);
        $this->db->bind(':password', $password); // Hashed password
        return $this->db->execute();
    }
}
?>