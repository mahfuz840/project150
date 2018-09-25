<?php

header('Content-Type: application/json');
/**
 * File to handle all API requests
 * Accepts GET and POST
 * 
 * Each request will be identified by TAG
 * Response will be JSON data

  /**
 * check for POST request 
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];

    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // response Array
    $response = array("error" => FALSE);

    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check for user
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) {
            // user found
            $response["error"] = FALSE;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];

            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["error"] = TRUE;
            $response["error_msg"] = "Incorrect email or password!";

            echo json_encode($response);
        }
    } else if ($tag == 'register') {
        // Request type is Register new user
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check if user is already existed
        if ($db->isUserExisted($email)) {
            // user is already existed - error response
            $response["error"] = TRUE;
            $response["error_msg"] = "User already existed";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password);
            if ($user != null) {
                // user stored successfully
                $response["error"] = FALSE;
                $response["uid"] = $user["unique_id"];

                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Error occured in Registartion";
                echo json_encode($response);
            }
        }
    } else if($tag == 'update'){
        $email = $_POST['email'];

        $isComplete = $db->isFormComplete($email);
        if (!$isComplete) {
            $response['error'] = FALSE;
            $response["completeness"] = FALSE;

            echo json_encode($response);
        } else if ($isComplete) {
            $response['error'] = FALSE;
            $response['completeness'] = TRUE;

            echo json_encode($response);
        }else {
            $response['error'] = TRUE;
            echo json_encode($response);
        }




    } else if ($tag == 'update_basic') {
        $uid = $_POST['uid'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $age = (int)$_POST['age'];
        $height = (float)$_POST['height'];
        $waist = (float)$_POST['waist'];
        $neck = (float)$_POST['neck'];
        $weight = (float)$_POST['weight'];

        $heightInMeter = $height * 0.3048;
        $heightInCM = $heightInMeter * 100;
        $waistInCM = $waist * 2.54;
        $neckInCM = $neck * 2.54;

        $bmi = $weight/($heightInMeter*$heightInMeter);
        $bmi = round($bmi, 2);
        
        $bf = 495 / ( 1.0324 - 0.19077 * log10( $waistInCM - $neckInCM ) + 0.15456 * log10( $heightInCM ) ) - 450;
        $bf = round( $bf, 2 );
        $response['bf'] = $bf;
        $response['height'] = $heightInCM;
        $bodyFatMass = $bf * $weight/100;
        $bodyFatMass = round($bodyFatMass, 2);
        $response['mass'] = $bodyFatMass;

        $activitiesQuery = $db->updateActivity($uid, $bmi, $bf, $bodyFatMass);
        $response['bmi'] = $bmi;
        $response['bf'] = $bf;
        $response['bodyFatMass'] = $bodyFatMass;

        if ($activitiesQuery) {
            $response['activities'] = 'done';
        } else {
            $response['activities'] = 'failed';
        }

        $query = $db->updateBasic($name, $age, $height, $weight, $waist, $neck, $email);

        if ($query) {
            $response['error'] = FALSE;
            $response['all'] = 'done';
            echo json_encode($response);
        } else {
            $response['error'] = TRUE;
            echo json_encode($response);
        }
    } else if ($tag == 'upload_distance') {
        $uid = $_POST['uid'];
        $distance = (float)$_POST['distance'];

        $caloriesBurned = $distance*(57/1000);

        $queryForRemainingCalorie = $db->calculateCalorie($uid, $distance, $caloriesBurned);
        if ($queryForRemainingCalorie) {
            $response['calorie_update'] = 'success';
        } else {
            $response['calorie_update'] = 'failed';
        }

        $queryForFat = $db->calculateFat($uid);

        // $success = $db->uploadDistance($uid, $distance);
        // if ($success) {
        //     $response['error'] = false;
        //     echo json_encode($response);
        // } else {
        //     $response['error'] = true;
        //     echo json_encode($response);
        // }
        if ($queryForFat) {
            $response['fat'] = 'done';
        } else {
            $response['fat'] = 'failed';
        } 

            echo json_encode($response); 

    } else if($tag == 'standing'){
        $ranking = array();
        $topTenQuery = $db->getStanding();
        if (!$topTenQuery) {
            $response['error'] = true;
            echo json_encode($response);
        } else {
            // $ranking['error'] = false;
            while ($row = $topTenQuery->fetch_assoc()) {
                $temp = [
                    'name' => $row["name"],
                    'fat' => $row["body_fat_percentage"]
                ];

                array_push($ranking, $temp);
            }
            echo json_encode($ranking);
        }

    }  else if ($tag == 'foodtips') {

        $tips = $db->getFoodTips();
        if (!$tips) {
            $response['error'] = true;
        } else {
            $response['error'] = false;
            $response['tips'] = $tips;
        }
        echo json_encode($response);

    } else if ($tag == 'profile'){


        $uid = $_POST['uid'];
        $result = $db->getProfileBasics($uid);
        if (!$result) {
            $response['error'] = true;
        } else {
            $response['error'] = false;
            $response['name'] = $result['name'];
            $response['email'] = $result['email'];
            $response['age'] = $result['age'];
            $response['height'] = $result['height'];
            $response['weight'] = $result['weight'];
            $response['waist'] = $result['waist'];
            $response['neck'] = $result['neck'];
        }

        echo json_encode($response);


    } else if ($tag == 'dashboard') {
        $uid = $_POST['uid'];
        $result = $db->getActivities($uid);
        if (!$result) {
            $response['error'] = true;
        } else {
            $response['error'] = false;
            $response['bmi'] = $result['bmi'];
            $response['body_fat_percentage'] = $result['body_fat_percentage'];
            $response['body_fat_mass'] = $result['body_fat_mass'];
            $response['total_distance'] = $result['total_distance'];
            $response['calories_to_burn'] = $result['calories_to_burn'];
            $response['remaining_calorie'] = $result['remaining_calorie'];
        }

        echo json_encode($response);

    }   else {
        // user failed to store
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknow 'tag' value. It should be either 'login' or 'register'";
        echo json_encode($response);
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameter 'tag' is missing!";
    echo json_encode($response);
}
?>
