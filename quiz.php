<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'koneksi.php';

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id_quiz"])) {
            $id_quiz = intval($_GET["id_quiz"]);
            get_quiz($id_quiz);
        } elseif (!empty($_GET["id_materi"])) {
            $id_materi = intval($_GET["id_materi"]);
            get_quiz_list($id_materi);
        } else {
            get_all_quizzes();
        }
        break;
    case 'POST':
        create_quiz();
        break;
    case 'PUT':
        $id_quiz = intval($_GET["id_quiz"]);
        update_quiz($id_quiz);
        break;
    case 'DELETE':
        $id_quiz = intval($_GET["id_quiz"]);
        delete_quiz($id_quiz);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}

function get_all_quizzes()
{
    global $koneksi;
    $query = "SELECT * FROM quiz";
    $result = $koneksi->query($query);
    $quizzes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $quizzes[] = $row;
    }
    echo json_encode($quizzes);
}

function get_quiz_list($id_materi)
{
    global $koneksi;
    $query = "SELECT * FROM quiz WHERE id_materi = $id_materi";
    $result = $koneksi->query($query);
    $quizzes = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $quizzes[] = $row;
    }
    echo json_encode($quizzes);
}

function get_quiz($id_quiz)
{
    global $koneksi;
    $query = "SELECT * FROM quiz WHERE id_quiz = $id_quiz";
    $result = $koneksi->query($query);
    $quiz = mysqli_fetch_assoc($result);
    echo json_encode($quiz);
}

function create_quiz()
{
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);
    $id_materi = $data["id_materi"];
    $question = $data["question"];
    $answer = $data["answer"];

    $query = "INSERT INTO quiz (id_materi, question, answer) VALUES ('$id_materi', '$question', '$answer')";
    if ($koneksi->query($query) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Quiz added successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to add quiz.'
        );
    }
    echo json_encode($response);
}

function update_quiz($id_quiz)
{
    global $koneksi;
    $data = json_decode(file_get_contents('php://input'), true);
    $id_materi = $data["id_materi"];
    $question = $data["question"];
    $answer = $data["answer"];

    $query = "UPDATE quiz SET id_materi = '$id_materi', question = '$question', answer = '$answer' WHERE id_quiz = $id_quiz";
    if ($koneksi->query($query) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Quiz updated successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to update quiz.'
        );
    }
    echo json_encode($response);
}

function delete_quiz($id_quiz)
{
    global $koneksi;
    $query = "DELETE FROM quiz WHERE id_quiz = $id_quiz";
    if ($koneksi->query($query) === TRUE) {
        $response = array(
            'status' => 'success',
            'message' => 'Quiz deleted successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to delete quiz.'
        );
    }
    echo json_encode($response);
}
