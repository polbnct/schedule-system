<?php
class Student {
    private $db;
    public function __construct(){ $this->db = new Database; }

    public function login($student_id, $password){
        $this->db->query('SELECT * FROM students WHERE student_id = :student_id');
        $this->db->bind(':student_id', $student_id);
        $row = $this->db->single();

        if($row && password_verify($password, $row->password)){
            return $row;
        } else {
            return false;
        }
    }

    public function getAttendanceHistory($student_db_id){
        $this->db->query('SELECT attendance_date, status FROM attendance WHERE student_id = :student_id ORDER BY attendance_date DESC');
        $this->db->bind(':student_id', $student_db_id);
        return $this->db->resultSet();
    }

    public function findById($student_id) {
        $this->db->query('SELECT id FROM students WHERE student_id = :student_id');
        $this->db->bind(':student_id', $student_id);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    public function register($data) {
        $this->db->query('INSERT INTO students (full_name, student_id, password, course_id, year_level) VALUES (:full_name, :student_id, :password, :course_id, :year_level)');
        $this->db->bind(':full_name', $data['full_name']);
        $this->db->bind(':student_id', $data['student_id']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':year_level', $data['year_level']);
        return $this->db->execute();
    }

    // --- THIS IS THE CORRECTED FUNCTION ---
    // Checks if the student has already filed attendance for a SPECIFIC DATE.
    public function hasMarkedAttendanceForDate($student_db_id, $date) {
        $this->db->query('SELECT id FROM attendance WHERE student_id = :student_id AND attendance_date = :attendance_date');
        $this->db->bind(':student_id', $student_db_id);
        $this->db->bind(':attendance_date', $date);
        
        // The reliable way: try to fetch a row.
        $row = $this->db->single();
        
        // If $row is not false (meaning a record was found), return true. Otherwise, return false.
        if($row){
            return true;
        } else {
            return false;
        }
    }

    // Marks the student's attendance for a specific date with a specific status.
    public function markAttendance($student_db_id, $date, $status) {
        // Insert the record into the database with the provided date and status
        $this->db->query('INSERT INTO attendance (student_id, attendance_date, status) VALUES (:student_id, :attendance_date, :status)');
        $this->db->bind(':student_id', $student_db_id);
        $this->db->bind(':attendance_date', $date);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }
}
?>