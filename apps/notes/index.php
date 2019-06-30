<?php

require_once(__DIR__.'/notes.php');
require_once(__DIR__.'/../../inc/init.php');

if(GetLanguageDir(USER_LANG)) {
    require(__DIR__.'/lang/'.USER_LANG.'/notes.php');
}else {
    require(__DIR__.'/lang/en/notes.php');
}

$notes = new Notes();
$page = @Post("page");

switch($page) {
    default:
    case "":
        $notes->DrawNotes();
    break;

    case "ShowNotes":
        $notes->ShowNotes();
    break;

    case "AddNotes":
        $id = @Post("id");
        if($id == "" || !is_numeric($id)) {
            $id = 0;
        }
        $notes->AddNotes($id);
    break;

    case "NewNotes":
        $title = @Post("title");
        $text = @Post("description");

        $query = mysqli_query($db, "INSERT INTO notes SET ip = '$ip', date = '$date', u_id = ".$user["u_id"].", title = '$title', description = '$text'");
        $error = $db->error;
        if($query) {
            mysqli_commit($db);
            $data = [
                "status" => "success",
                "message" => $lang["note_added_success"]
            ];
        }else {
            mysqli_rollback($db);
            $data = [
                "status" => "warning",
                "message" => $lang["note_error_adding"],
                "code" => $error
            ];
        }
        echo json_encode($data);
    break;

    case "UpdateNotes":
        $title = @Post("title");
        $text = @Post("description");
        $id = @Post("id");

        if(!$id == "" || is_numeric($id)) {
            $query = mysqli_query($db, "SELECT note_id, u_id FROM notes WHERE note_id = '$id' && u_id = ".$user["u_id"]);
            $count = mysqli_num_rows($query);

            if($count > 0) {
                $query = mysqli_query($db, "UPDATE notes SET date = '$date', title = '$title', description = '$text' WHERE note_id = '$id'");
                $error = $db->error;
                if($query) {
                    mysqli_commit($db);
                    $data = [
                        "status" => "success",
                        "message" => $lang["note_updated_success"]
                    ];
                }else {
                    mysqli_rollback($db);
                    $data = [
                        "status" => "warning",
                        "message" => $lang["note_error_updating"],
                        "code" => $error
                    ];
                }
            }else {
                $data = [
                    "status" => "error",
                    "message" => $lang["authorized_update_note_error"]
                ];
            }
            echo json_encode($data);
        }
    break;

    case "DeleteNote":
        $id = @Post("id");

        if(!$id == "" || is_numeric($id)) {
            $query = mysqli_query($db, "SELECT note_id, u_id FROM notes WHERE note_id = '$id' && u_id = ".$user["u_id"]);
            $count = mysqli_num_rows($query);

            if($count > 0) {
                $query = mysqli_query($db, "DELETE FROM notes WHERE note_id = '$id'");
                $error = $db->error;
                if($query) {
                    mysqli_commit($db);
                    $data = [
                        "status" => "success",
                        "message" => $lang["note_deleted_success"]
                    ];
                }else {
                    mysqli_rollback($db);
                    $data = [
                        "status" => "warning",
                        "message" => $lang["note_deleting_error"],
                        "error" => $error
                    ];
                }
            }else {
                $data = [
                    "status" => "error",
                    "message" => $lang["authorized_delete_note_error"]
                ];
            }
            echo json_encode($data);
        }
    break;
}

?>