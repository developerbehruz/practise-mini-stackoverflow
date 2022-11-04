<?php
    include "db.php";

    $data = ['ok'=>false, 'code'=>null, 'message'=>null, 'result'=>[]];

    if (isset($_REQUEST['do'])) {
        if ($_REQUEST['do'] == 'signup') {
            if (isset($_REQUEST['name']) && isset($_REQUEST['mail']) && isset($_REQUEST['password'])) {
                $name = $_REQUEST['name'];
                $mail = $_REQUEST['mail'];
                $password = $_REQUEST['password'];

                $slt = "SELECT * FROM users WHERE mail = '$mail'";
                $query = mysqli_query($conn, $slt);
                if (mysqli_num_rows($query)>0) {
                    $data['code'] = 407;
						$data['message'] = $_REQUEST['mail'] . " the mail already exists";
                }else{
                    $rand = rand(time(), 10000);
                    $ins = "INSERT INTO users (name,mail,password,user_id) VALUES ('{$name}','{$mail}','{$password}','{$rand}')" or die(mysqli_error($conn));
                    $query = mysqli_query($conn, $ins);

                    $slt = "SELECT * FROM users WHERE user_id = '$rand'";
                    $result = mysqli_query($conn, $slt);
                    
                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = 'Registered successfully';
                    $data['result'][] = mysqli_fetch_assoc($result);
                }
            }else{
                $data['code'] = 402;
				$data['message'] = "Name, mail, password are required";
            }
        }else if($_REQUEST['do'] == 'checkUser') {
            if (isset($_REQUEST['user_id'])) {
                $user_id = $_REQUEST['user_id'];
                $slt = "SELECT * FROM users WHERE user_id = '$user_id'";
                $query = mysqli_query($conn, $slt);
                if (mysqli_num_rows($query)>0) {
                    $slt = "SELECT * FROM users WHERE user_id = '$user_id'";
                    $result = mysqli_query($conn, $slt);

                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = 'This user is found';
                    $data['result'][] = mysqli_fetch_assoc($result);
                }else{
                    $data['code'] = 404;
                    $data['message'] = 'This user is not found';
                }
            }else{
                $data['code'] = 402;
				$data['message'] = "User id is required";
            }
        }else if($_REQUEST['do'] == 'addQuestion') {
            if (isset($_REQUEST['user_id']) && isset($_REQUEST['title']) && isset($_REQUEST['question'])){
                $user_id = $_REQUEST['user_id'];
                $title = $_REQUEST['title'];
                $question = $_REQUEST['question'];

                $slt = "SELECT * FROM users WHERE user_id = '$user_id'";
                $query = mysqli_query($conn, $slt);

                foreach($query as $key => $value) {
                    $name = $value['name'];
                    if (mysqli_num_rows($query)>0) {
                        $date = date("Y-m-d");
                        $ins = "INSERT INTO questions (user_id,name,title,question,date) VALUES ('{$user_id}','{$name}','{$title}','{$question}', '{$date}')" or die(mysqli_error($conn));
                        $query = mysqli_query($conn, $ins);
                        
                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = 'Question was added successfully';
                        // $data['result'][] = mysqli_fetch_assoc($result);
                    }else{
                        $data['code'] = 407;
                        $data['message'] = $_REQUEST['user_id'] . " the user id invalid";
                    }
                }
            }else{
                $data['code'] = 402;
				$data['message'] = "User id, title, question are required";
            }
        }else if($_REQUEST['do'] == 'getAllQuestion') {
            $slt = "SELECT * FROM questions WHERE id";
            $query = mysqli_query($conn, $slt);
            if (mysqli_num_rows($query)>0) {
                $slt = "SELECT * FROM questions ORDER BY id DESC";
                $result = mysqli_query($conn, $slt);

                foreach($result as $key => $value) {
                    $data['result'][] = $value;
                }

                $data['ok'] = true;
                $data['code'] = 200;
                $data['message'] = 'This data is question';
            }else{
                $data['code'] = 404;
                $data['message'] = 'Quiz not found';
            }
        }else if($_REQUEST['do'] == 'addAnswers') {
            if (isset($_REQUEST['q_user_id']) && isset($_REQUEST['a_user_id']) && isset($_REQUEST['q_id']) && isset($_REQUEST['answers'])) {
                $q_user_id = $_REQUEST['q_user_id'];
                $a_user_id = $_REQUEST['a_user_id'];
                $q_id = $_REQUEST['q_id'];
                $answers = $_REQUEST['answers'];

                $slt = "SELECT * FROM questions WHERE user_id = '$q_user_id'";
                $query = mysqli_query($conn, $slt);
                if (mysqli_num_rows($query)>0) {
                    $slt = "SELECT * FROM users WHERE user_id = '$a_user_id'";
                    $query = mysqli_query($conn, $slt);
                    foreach($query as $key => $value) {
                        $u_name = $value['name'];

                        if (mysqli_num_rows($query)>0) {
                            $slt = "SELECT * FROM questions WHERE user_id = '$q_user_id' AND id = '$q_id'";
                            $query = mysqli_query($conn, $slt);

                            foreach($query as $key => $value) {
                                $q_title = $value['title'];
                                $question = $value['question'];

                                if (mysqli_num_rows($query)>0) {
                                    $date = date("Y-m-d");

                                    $ins = "INSERT INTO answers (q_user_id,a_user_id,a_name,q_id,q_title, question,answers,date) VALUES ('{$q_user_id}','{$a_user_id}','{$u_name}','{$q_id}','{$q_title}','{$question}','{$answers}','{$date}')" or die(mysqli_error($conn));
                                    $query = mysqli_query($conn, $ins);

                                    $data['ok'] = true;
                                    $data['code'] = 200;
                                    $data['message'] = 'Anwers was added successfully';
                                }else{
                                    $data['code'] = 407;
                                    $data['message'] = $_REQUEST['q_id'] . " the question_id invalid";
                                }
                            }
                        }else{
                            $data['code'] = 407;
                            $data['message'] = $_REQUEST['a_user_id'] . " the a_user_id invalid";
                        }
                    }
                }else{
                    $data['code'] = 407;
					$data['message'] = $_REQUEST['q_user_id'] . " the q_user_id invalid";
                }
            }else{
                $data['code'] = 402;
                $data['message'] = "User_id, answers user_id, question_id and answers required";
            }
        }else if($_REQUEST['do'] == 'getAllAnswers') {
            if (isset($_REQUEST['q_user_id']) && isset($_REQUEST['q_id'])) {
                $q_user_id = $_REQUEST['q_user_id'];
                $q_id = $_REQUEST['q_id'];
                $slt = "SELECT * FROM answers WHERE q_user_id = '$q_user_id' AND q_id = '$q_id'";
                $query = mysqli_query($conn, $slt);

                foreach($query as $key => $value) {
                    $data['result'][] = $value;
                }

                $data['ok'] = true;
                $data['code'] = 200;
                $data['message'] = 'This data is question';
            }else{
                $data['code'] = 402;
				$data['message'] = "q_user_id and q_id are required";
            }
        }
    }else{
		$data['code'] = 400;
		$data['message'] = 'Method not found';
	}
	echo json_encode($data,  JSON_PRETTY_PRINT);
?>