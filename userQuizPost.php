<?php

include 'koneksi.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}


$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'POST':
        create_user_quiz();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function create_user_quiz()
{
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);
    $id_quiz = $data["id_quiz"];
    $submission_data = $data["submission_data"];

    // Fetch correct answer from quiz table
    $query = "SELECT answer FROM quiz WHERE id_quiz = $id_quiz";
    $result = $koneksi->query($query);

    if ($result) {
        $quiz = mysqli_fetch_assoc($result);
        $correct_answer = $quiz['answer'];

        // Calculate score
        $score = ($submission_data == $correct_answer) ? 10 : 0;

        // Save data to user_quiz table
        $insert_query = "INSERT INTO user_quiz ( id_quiz, submission_data, score) VALUES ( '$id_quiz', '$submission_data', '$score')";
        if ($koneksi->query($insert_query) === TRUE) {
            $last_insert_id = $koneksi->insert_id; // Get last inserted id_user_quiz

            $response = array(
                'status' => 'success',
                'message' => 'User quiz submission added successfully.',
                'data' => array(
                    'id_user_quiz' => $last_insert_id, // Include id_user_quiz in response

                    'id_quiz' => $id_quiz,
                    'submission_data' => $submission_data,
                    'score' => $score
                )
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Failed to add user quiz submission.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to fetch quiz answer.'
        );
    }

    echo json_encode($response);
}
