<?php

class DB_Functions {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $con = $this->db->connect();

        $result = mysqli_query($con, "INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES('$uuid', '$name', '$email', '$encrypted_password', '$salt', NOW())");



        // check for successful store
        if ($result) {
            // get user details 
            $id = mysqli_insert_id($con);  // last inserted id

        	$second_query = mysqli_query($con, "INSERT INTO activities(uid, bmi, remaining_calorie, total_distance, calories_to_burn)
        	VALUES('$uuid', null, null, 0, null)");

            $res = mysqli_query($con, "SELECT * FROM `users` WHERE id = '$id'") or die(mysql_error());
            
            // return user details
            $res = mysqli_fetch_array($res);

            return $res;
        } else {
            return false;
        }
    }

    public function getID(){
    	$id = mysqli_insert_id($con);
    	return $id;
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
    	$con = $this->db->connect();
        $result = mysqli_query($con, "SELECT * FROM `users` WHERE email = '$email'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
    	$con = $this->db->connect();
        $result = mysqli_query($con, "SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }

    public function isFormComplete($email){
    	$con = $this->db->connect();
    	$result = mysqli_query($con, "SELECT * from `users` WHERE email = '$email'") or die(mysql_error());
    	$fetchedResult = mysqli_fetch_array($result);
    	$age = $fetchedResult['age'];
    	$height = $fetchedResult['height'];
    	$weight = $fetchedResult['weight'];

    	if ($age == null || $height == null || $weight == null) {
    		return false;
    	} else {
    		return true;
    	}
    }

    public function updateBasic($name, $age, $height, $weight, $waist, $neck, $email){
    	$con = $this->db->connect();

    	$query = mysqli_query($con, "UPDATE `users` SET name = '$name',
    	 age = $age,
    	  height = $height,
    	   weight = $weight,
    	   waist = $waist,
    	   neck = $neck
    	    WHERE email = '$email'");

    	if ($query) {
    		return true;
    	} else {
    		return false;
    	}
    }

    public function uploadDistance($uid, $distance){
    	$con = $this->db->connect();

    	$query = mysqli_query($con, "UPDATE `activities` SET total_distance = '$distance' WHERE uid = '$uid'") or die(mysql_error());

    	if ($query) {
    		return true;
    	} else {
    		return false;
    	}
    }

    public function calculateCalorie($uid, $distance, $caloriesBurned){
    	$con = $this->db->connect();

    	$query = mysqli_query($con, "SELECT * FROM `activities` WHERE uid = '$uid'");
    	if ($query) {
    		$result = mysqli_fetch_array($query);
    		$remainingCalorie = $result['remaining_calorie'];
    		$newRemainingCalorie = (int)($remainingCalorie - $caloriesBurned);
    		$updateQuery = mysqli_query($con, "UPDATE activities SET remaining_calorie='$newRemainingCalorie' WHERE uid = '$uid'");

    		$totalDistance = $result['total_distance'];
    		$newTotalDistance = (int)($totalDistance + $distance);
    		$updateDistance = mysqli_query($con, "UPDATE activities SET total_distance='$newTotalDistance' WHERE uid = '$uid'");

    		if ($updateQuery) {
    			return true;
    		} else{
    			return false;
    		}
    	} else {
    		return false;
    	}
    }

    public function calculateFat($uid){
    	$con = $this->db->connect();

    	$queryForFat = mysqli_query($con, "SELECT * FROM activities WHERE uid='$uid'");
    	$queryForUser = mysqli_query($con, "SELECT * FROM users WHERE unique_id='$uid'");

    	if ($queryForFat && $queryForUser) {
    		$fatResult = mysqli_fetch_array($queryForFat);
    		$userResult = mysqli_fetch_array($queryForUser);

    		$remainingCalorie = $fatResult['remaining_calorie'];
    		$caloriesToBurn = $fatResult['calories_to_burn'];
    		$fatMass = $fatResult['body_fat_mass'];
    		$weight = $userResult['weight'];
    		$waist = $userResult['waist'];
    		$neck = $userResult['neck'];
    		$height = $userResult['height'];

    		$heightInMeter = $height * 0.3048;
        	$heightInCM = $heightInMeter * 100;
        	$waistInCM = $waist * 2.54;
        	$neckInCM = $neck * 2.54;

    		$burnedCalorie = $caloriesToBurn - $remainingCalorie;
    		$burnedFatInKG = $burnedCalorie/(3500*2.205);
    		$newFatMass = round($fatMass - $burnedFatInKG, 2);
    		$newWeight = round($weight - $burnedFatInKG, 2);

    		$newBodyFatPercentage = 495 / ( 1.0324 - 0.19077 * log10( $waistInCM - $neckInCM ) + 0.15456 * log10( $heightInCM ) ) - 450;
    		$newBodyFatPercentage = $newBodyFatPercentage/100;
        	$newBodyFatPercentage = $newBodyFatPercentage * 100;

    		$newBodyFatPercentage = round($newBodyFatPercentage, 2);
    		$newBodyFatMass = $newBodyFatPercentage * $newWeight;
    		$newBodyFatMass = round($newBodyFatMass, 2);
    		$newBMI = $newWeight/($heightInMeter * $heightInMeter);
    		$newBMI = round($newBMI, 2);

    		$queryForUserUpdate = mysqli_query($con, "UPDATE `users` SET weight = $newWeight WHERE unique_id = '$uid'");
    		$queryForActivityUpdate = mysqli_query($con, "UPDATE `activities` SET bmi = $newBMI,
    			body_fat_percentage = $newBodyFatPercentage,
    			body_fat_mass = $newBodyFatMass 
    			WHERE uid = '$uid'");

    		if($queryForUserUpdate && $queryForActivityUpdate){
    			return true;
    		} else {
    			return false;
    		}
    	} else {
    		return false;
    	}
    }

    public function updateActivity($uid, $bmi, $bf, $bodyFatMass){

    	$con = $this->db->connect();

    	$caloriesToBurn = (int)($bodyFatMass * 2.205 * 3500);
    	$remainingCalorie = $caloriesToBurn;

    	$query = mysqli_query($con, "UPDATE activities SET body_fat_percentage = $bf,
    	 bmi = $bmi,
    	 remaining_calorie = $remainingCalorie,
    	 calories_to_burn = $caloriesToBurn,
    	 body_fat_mass = $bodyFatMass
    	 WHERE uid = '$uid'");

    	if ($query) {
    		return true;
    	} else {
    		return false;
    	}
    }

    public function getStanding(){
    	$con = $this->db->connect();
    	$test = mysqli_query($con, "SELECT name,body_fat_percentage
    		FROM users,activities
    		WHERE users.unique_id=activities.uid
    		ORDER BY body_fat_percentage ASC
    		LIMIT 10") or die();

    	// $queryString = "SELECT name,body_fat_percentage
    	// 	FROM users,activities
    	// 	WHERE users.unique_id=activities.uid
    	// 	ORDER BY body_fat_percentage ASC
    	// 	LIMIT 10";
    	// 	$query = $con->prepare($queryString);
    	// 	$query->execute();
    	// if ($query) {
    	// 	return $query;
    	// } else {
    	// 	return false;
    	// }
    	return $test;
    }

    public function getFoodTips(){
    	$con = $this->db->connect();
    	$tipsQuery = mysqli_query($con, "SELECT tips from tips WHERE t_id=(SELECT MAX(t_id) from tips)");
    	if (!$tipsQuery) {
    		return false;
    	} else {
    		$fetchedResult = mysqli_fetch_array($tipsQuery);
    		$tips = $fetchedResult['tips'];
    		return $tips;
    	}
    }

    public function getProfileBasics($uid){
    	$con = $this->db->connect();
    	$profileQuery = mysqli_query($con, "SELECT name, email, age, height, weight, waist, neck FROM users where unique_id = '$uid'");
    	$result = mysqli_fetch_array($profileQuery);
    	if (!$profileQuery) {
    		return false;
    	} else {
    		return $result;
    	}
    }

    public function getActivities($uid){
    	$con = $this->db->connect();
    	$activitiesQuery = mysqli_query($con, "SELECT body_fat_percentage, bmi, total_distance, body_fat_mass, calories_to_burn, remaining_calorie FROM activities WHERE uid = '$uid'");
    	if (!$activitiesQuery) {
    		return false;
    	} else {
    		$result = mysqli_fetch_array($activitiesQuery);
    		return $result;
    	}
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

    public function getConnection(){
    	$con = $this->db->connect();
    	return $con;
    }

}

?>
