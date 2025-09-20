<?php

class Course {
    private $db;
    public function __construct(){ $this->db = new Database; }

    public function addCourse($course_name){
        $this->db->query('INSERT INTO courses (course_name) VALUES (:course_name)');
        $this->db->bind(':course_name', $course_name);
        return $this->db->execute();
    }

    public function getCourses(){
        $this->db->query('SELECT * FROM courses ORDER BY course_name ASC');
        return $this->db->resultSet();
    }

    // --- NEW METHOD ---
    // Fetches a single course by its unique ID.
    public function getCourseById($id) {
        $this->db->query('SELECT * FROM courses WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // --- NEW METHOD ---
    // Updates the name of an existing course.
    public function updateCourse($id, $course_name) {
        $this->db->query('UPDATE courses SET course_name = :course_name WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':course_name', $course_name);
        return $this->db->execute();
    }

    // --- NEW METHOD ---
    // Deletes a course from the database.
    public function deleteCourse($id) {
        $this->db->query('DELETE FROM courses WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
?>