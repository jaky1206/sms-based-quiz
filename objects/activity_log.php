<?php
class ActivityLog{
 
    // database connection and table name
    private $conn;
    private $table_name = "robi_jhakanaka_contest.activity_log";
 
    // object properties
    public $activity_id;
    public $msisdn;
    public $text_msg;
    public $activity_date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        try{
 
            // insert query
            $query = "INSERT INTO " . $this->table_name . " SET msisdn=:msisdn,text_msg=:text_msg,activity_date=CURRENT_TIMESTAMP()";
 
            // prepare query for execution
            $stmt = $this->conn->prepare($query);
 
            // sanitize
            $msisdn=htmlspecialchars(strip_tags($this->msisdn));
            $text_msg=htmlspecialchars(strip_tags($this->text_msg));
 
            // bind the parameters
            $stmt->bindParam(':msisdn', $msisdn);
            $stmt->bindParam(':text_msg', $text_msg);
            
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
    
}