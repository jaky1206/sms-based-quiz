<?php
class Question{
 
    // database connection and table name
    private $conn;
    private $table_name = "robi_jhakanaka_contest.questions";
 
    // object properties
    public $id;
    public $question_id;
    public $full_question;
    public $word_count_full_question;
    public $question_english;
    public $question_bangla;
    public $word_count_bangla;
    public $option_a;
    public $option_a_bangla;
    public $option_b;
    public $option_b_bangla;
    public $answer;
 
    public function __construct($db){
        $this->conn = $db;
    }
    public function readOne(){
        // select one record
        $query = "SELECT r1.id,r1.question_id,r1.full_question,r1.word_count_full_question,r1.question_english,
                    r1.question_bangla,r1.word_count_bangla,r1.option_a,r1.option_a_bangla,r1.option_b,
                    r1.option_b_bangla,r1.answer
                    FROM " . $this->table_name . " AS r1
                    WHERE r1.id = :id";
    
        //prepare query for excecution
        $stmt = $this->conn->prepare($query);
    
        $id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return json_encode($results);
    }
    public function readOneRandom(){
        // select one record
        $query = "SELECT r1.id,r1.question_id,r1.full_question,r1.word_count_full_question,r1.question_english,
                    r1.question_bangla,r1.word_count_bangla,r1.option_a,r1.option_a_bangla,r1.option_b,
                    r1.option_b_bangla,r1.answer
                    FROM " . $this->table_name . " AS r1 JOIN
                    (
                        SELECT CEIL(RAND() *
                            (
                                SELECT MAX(q.id) FROM 
                                (
                                    SELECT a.id FROM " . $this->table_name . " AS a
                                    WHERE a.id<>:id
                                ) AS q
                            )
                        ) AS id
                    )
                    AS r2
                    WHERE r1.id >= r2.id
                    ORDER BY r1.id ASC
                    LIMIT 1";
    
        //prepare query for excecution
        $stmt = $this->conn->prepare($query);
    
        $id=htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return json_encode($results);
    }
    
}