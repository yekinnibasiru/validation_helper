<?php 


class Validation {

    /**
     * Author: Yekinni Basiru Kola
     * Description: This is a lightweight helper class for validation
     * Language: PHP
     * License: MIT
     */


     /**
      * @var Array $errors
      */

    public $errors=[];

    /**
     * @var String database connection set to null(default)
     */

    private $conn=null;

    /**
     * @param String 
     */

    public function __construct($conn){
        $this->conn=$conn;
    }

    /**
     * @param String $fieldname
     * @return String `
     */

    public function Input($fieldname){
        if ($_SERVER['REQUEST_METHOD']== 'POST' || $_SERVER['REQUEST_METHOD']== 'post'){
            return $this->clean_input($_POST[$fieldname]);
        }
        elseif ($_SERVER['REQUEST_METHOD']== 'GET' || $_SERVER['REQUEST_METHOD']== 'get'){
            return $this->clean_input($_GET[$fieldname]);
        }
    }

    /**
     * @param String $fieldname
     * @param String $label
     * @param String $rules
     * @param Array $errorMessages
     */

    public function validate($fieldname,$label,$rules,$errorMessages=array()){
        $Allrules=preg_split('/[:\.\|\[\]]+/',$rules);

        $fieldvalue=$this->input($fieldname);

        //Check for required rule
        if(in_array('required',$Allrules)){
            if(empty($fieldvalue)){
                if(is_null($this->errorMessageProcess('required',$errorMessages))){
                    $this->errors[$fieldname][]=$label." is required";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('required',$errorMessages);
                }
                
            }
        }

        //Check for valid_email rule
        if(in_array('valid_email',$Allrules)){
            if(!filter_var($fieldvalue,FILTER_VALIDATE_EMAIL)){
                if(is_null($this->errorMessageProcess('valid_mail',$errorMessages))){
                    $this->errors[$fieldname][]=$label." is not a valid email";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('valid_email',$errorMessages);
                }
                
            }
        }

        //Check for valid_url rule
        if(in_array('valid_url',$Allrules)){
            if(!filter_var($fieldvalue,FILTER_VALIDATE_URL)){
                if(is_null($this->errorMessageProcess('valid_url',$errorMessages))){
                    $this->errors[$fieldname][]=$label." is not a valid url";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('valid_url',$errorMessages);
                }
                
            }
        }

        //Check for is_alpha rule
        if(in_array('is_alpha',$Allrules)){
            if(!ctype_alpha($fieldvalue)){
                if(is_null($this->errorMessageProcess('is_alpha',$errorMessages))){
                    $this->errors[$fieldname][]=$label." can only contain alphabetical character";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('is_alpha',$errorMessages);
                }
                
            }
        }

        //Check for is_numeric rule
        if(in_array('is_numeric',$Allrules)){
            if(!is_numeric($fieldvalue)){
                if(is_null($this->errorMessageProcess('is_numeric',$errorMessages))){
                    $this->errors[$fieldname][]=$label." can only contain numerical value";
                }
                else{
                    $this->error[$fieldname][]=$this->errorMessageProcess('is_numeric',$errorMessages);
                }
                
            }
        }

        //Checks for alphanum rule
        if(in_array('is_alphanum',$Allrules)){
            if(!ctype_alnum($fieldvalue)){
                if(is_null($this->errorMessageProcess('is_alphanum',$errorMessages))){
                    $this->errors[$fieldname][]=$label." can only contain alphabetical and numeric characters";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('is_alphanum',$errorMessages);
                }
                
            }
        }

        //Check for min_len rule
        if(in_array('min_len',$Allrules)){
            $minIndex=array_search('min_len',$Allrules);
            $minValueIndex=$minIndex + 1;
            $minValue=$Allrules[$minValueIndex];

            if(strlen($fieldvalue) < $minValue){
                if(is_null($this->errorMessageProcess('min_len',$errorMessages))){
                    $this->errors[$fieldname][]=$label." cannot be less than $minValue characters";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('min_len',$errorMessages);
                }
                
            }
        }

        //Check for max_len rule
        if(in_array('max_len',$Allrules)){
            $maxIndex=array_search('max_len',$Allrules);
            $maxValueIndex=$maxIndex + 1;
            $maxValue=$Allrules[$maxValueIndex];

            if(strlen($fieldvalue) > $maxValue){
                if(is_null($this->errorMessageProcess('max_len',$errorMessages))){
                    $this->errors[$fieldname][]=$label." cannot be more than $maxValue characters";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('max_len',$errorMessages);
                }
                
            }
        }

        //Check for matches rule
        if(in_array('matches',$Allrules)){
            $targetFieldIndex=array_search('matches',$Allrules) + 1;
            $fieldval=$Allrules[$targetFieldIndex];
            $targetValue=$this->Input($fieldval);
            if($targetValue !== $fieldvalue){
                if(is_null($this->errorMessageProcess('matches',$errorMessages))){
                    $this->errors[$fieldname][]=$label." does not match";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('matches',$errorMessages);
                }
            }
        }

        //Check for is_unique rule
        if(in_array('is_unique',$Allrules)){
            $uniqueIndex=array_search('is_unique',$Allrules);
            $uniqueTableIndex=$uniqueIndex + 1;
            $uniqueColumnIndex=$uniqueIndex + 2;
            $table=$Allrules[$uniqueTableIndex];
            $column=$Allrules[$uniqueColumnIndex];
            $escapeValue=$this->conn->real_escape_string($fieldvalue);

            $query="SELECT * FROM $table WHERE $column='$escapeValue'";
            $result=$this->conn->query($query);
           
            if($result->num_rows > 0){
                if(is_null($this->errorMessageProcess('is_unique',$errorMessages))){
                    $this->errors[$fieldname][]=$label." is already used";
                }
                else{
                    $this->errors[$fieldname][]=$this->errorMessageProcess('is_unique',$errorMessages);
                }
                
            }
        }
    }

    /**
     * @return Boolean 
     */

    //Check if there is an error message
    public function run(){
        if(count($this->errors) > 0){
            return false;
        }
        return true;
    }

    /**
     * @return Array
     */

    //Return array of errors by bailing each field set of errors

    public function validationErrors(){
        $errors=array();
        foreach($this->errors as $fielderror => $error){
            array_push($errors,$error[0]);
        }
        return $errors;
    }

    /**
     * @return String 
     */
    
    //Return first most error of the particular fieldname error
    public function formError($fieldname){
        if(array_key_exists($fieldname,$this->errors)){
            return $this->errors[$fieldname][0];
        }
    }

    /**
     * @param String $data
     * @return String
     */
    
    //Trim,htmlspecialcharacter,stripslaches data passed
    private function clean_input($data){
        $data=trim($data);
        $data=htmlspecialchars($data);
        $data=stripcslashes($data);
        return $data;
    }

    /**
     * @param String $ruleKey
     * @param Array $errorMessages
     * @return String
     */

     //Chech if custom error message is set
    private function errorMessageProcess($ruleKey,$errorMessages){
        if(!empty($errorMessages) && array_key_exists($ruleKey,$errorMessages)){
            return $errorMessages[$ruleKey];
        }
        return null;
    }

}



?>