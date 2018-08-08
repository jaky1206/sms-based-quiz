<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "robi_jhakanaka_contest.users";
 
    // object properties
    public $user_id;
    public $msisdn;
    public $registration_date;
    public $user_status;
    public $last_activity_date;
    public $last_question_id;
    public $user_score;
 
    public function __construct($db){
        $this->conn = $db;
    }
    public function readOne(){
        $query = "SELECT u.user_id,u.msisdn,u.registration_date,u.user_status,u.last_activity_date,
                u.last_question_id,u.user_score
                FROM " . $this->table_name . " AS u WHERE u.msisdn = :msisdn";

        //prepare query for excecution
        $stmt = $this->conn->prepare($query);
    
        $msisdn=htmlspecialchars(strip_tags($this->msisdn));
        $stmt->bindParam(':msisdn', $msisdn);
        $stmt->execute();
        
        $count = $stmt->rowCount();

        $results=$stmt->fetchAll(PDO::FETCH_ASSOC);

        return json_encode(($count != 0) ? $results : $count);
    }
    public function create(){
        try{
 
            // insert query
            $query = "INSERT INTO " . $this->table_name . " SET msisdn=:msisdn,registration_date=CURRENT_TIMESTAMP(),last_activity_date=CURRENT_TIMESTAMP()";
 
            // prepare query for execution
            $stmt = $this->conn->prepare($query);
 
            // sanitize
            $msisdn=htmlspecialchars(strip_tags($this->msisdn));
 
            // bind the parameters
            $stmt->bindParam(':msisdn', $msisdn);
            
            // Execute the query
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
 
        }
 
        // show error if any
        catch(PDOException $exception){
            die('ERROR: ' . $exception->getMessage());
        }
    }
    public function update(){ 
        $query = "UPDATE " . $this->table_name . "
                    SET user_status=:user_status, last_activity_date=CURRENT_TIMESTAMP(),
                    last_question_id=:last_question_id, user_score=:user_score
                    WHERE msisdn=:msisdn";
    
        //prepare query for excecution
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $user_status=htmlspecialchars(strip_tags($this->user_status));
        $last_question_id=htmlspecialchars(strip_tags($this->last_question_id));
        $user_score=htmlspecialchars(strip_tags($this->user_score));
        $msisdn=htmlspecialchars(strip_tags($this->msisdn));
    
        // bind the parameters
        $stmt->bindParam(':user_status', $user_status);
        $stmt->bindParam(':last_question_id', $last_question_id);
        $stmt->bindParam(':user_score', $user_score);
        $stmt->bindParam(':msisdn', $msisdn);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
    
}