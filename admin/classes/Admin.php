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

    public function getExcuseLetters($course_id = null) {
        // This query joins the three tables to get all the data we need in one go
        $query = "SELECT 
                    el.id, 
                    el.absence_date,
                    el.reason,
                    el.status,
                    el.admin_remarks,
                    s.full_name AS student_name, 
                    c.course_name 
                  FROM excuse_letters AS el
                  JOIN students AS s ON el.student_db_id = s.id
                  JOIN courses AS c ON el.course_id = c.id";
        
        // If a course ID is provided, add a WHERE clause to filter the results
        if ($course_id) {
            $query .= " WHERE el.course_id = :course_id";
        }
        
        $query .= " ORDER BY el.status = 'Pending' DESC, el.submitted_at DESC";
        
        $this->db->query($query);

        // Bind the course_id parameter only if it was provided
        if ($course_id) {
            $this->db->bind(':course_id', $course_id);
        }

        return $this->db->resultSet();
    }

    public function updateExcuseStatus($letter_id, $status, $remarks) {
        $this->db->query('UPDATE excuse_letters SET status = :status, admin_remarks = :remarks WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':remarks', $remarks);
        $this->db->bind(':id', $letter_id);
        
        return $this->db->execute();
    }

    public function getAllCourses() {
        $this->db->query('SELECT id, course_name FROM courses ORDER BY course_name ASC');
        return $this->db->resultSet();
    }
}
?>