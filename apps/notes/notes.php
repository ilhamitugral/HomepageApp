<?php

require_once(__DIR__."/../../inc/init.php");

class Notes {

    private $_lang;

    private function GetLanguageFile() {
        if(GetLanguageDir(USER_LANG)) {
            require(__DIR__.'/lang/'.USER_LANG.'/notes.php');
        }else {
            require(__DIR__.'/lang/en/notes.php');
        }
        return $lang;
    }

    public function DrawNotes() {
        $_lang = $this->GetLanguageFile();
        global $conf;
        ?>
        <script src="<?php echo $conf["site_url"]; ?>/apps/notes/notes.js"></script>
        <section class="section" data-name="Notes">
            <span class="title"><i class="fa fa-file"></i>&nbsp;<?php echo $_lang["notes"]; ?></span>
            <div class="notes">
                <?php $this->ShowNotes(); ?>
            </div>
        </section>
        <?php
    }

    public function AddNotes($id) {
        $_lang = $this->GetLanguageFile();
        global $user, $db;

        if($id == 0 || $id == "" || !is_numeric($id)) {
            $type = 'AddNote';
            $addClass = 'new';
            $id = "";
            $title = "";
            $text = "";
            $delete = "";
        }else {
            $query = mysqli_query($db, "SELECT note_id, u_id, title, description FROM notes WHERE note_id = '$id' && u_id = ".$user["u_id"]);
            $count = mysqli_num_rows($query);

            if($count > 0) {
                $row = mysqli_fetch_assoc($query);

                $type = "UpdateNote";
                $addClass = 'update';
                $id = $row["note_id"];
                $title = $row["title"];
                $text = htmlspecialchars_decode($row["description"]);
                $delete = '<div class="center"><a href="#" class="text-danger" onclick="DeleteNote('.$id.'); return false;"><i class="fa fa-trash"></i>&nbsp;'.$_lang["delete_note"].'</a></div>';
            }else {
                $type = "AddNote";
                $addClass = 'new';
                $id = "";
                $title = "";
                $text = "";
                $delete = "";
            }
        }

        ?>
        <div class="notes-menu <?php echo $addClass; ?>">
            <div class="left"><a href="#" class="text-warning" onclick="ShowNotesPage(); return false;"><i class="fa fa-arrow-left"></i>&nbsp;<?php echo $_lang["return_back"]; ?></a></div>
            <?php echo $delete; ?>
            <div class="right"><a href="#" class="text-success" onclick="<?php echo $type.'('.$id.')'; ?>; return false;"><i class="fa fa-save"></i>&nbsp;<?php echo $_lang["save_note"]; ?></a></div>
        </div>
        <div class="note-text">
            <input type="text" class="title" id="notes-title" value="<?php echo $title; ?>" placeholder="<?php echo $_lang["note_title"]; ?>" maxlength="256" onkeypress="textAreaCheck(event);"/>
            <div class="description" id="notes-description" placeholder="<?php echo $_lang["note_description"]; ?>" contenteditable="true"><?php echo $text; ?></div>
        </div>
        <?php
    }

    public function ShowNote($id) {

    }

    public function ShowNotes() {
        global $db, $user;
        $_lang = $this->GetLanguageFile();
        if(isset($_SESSION['login'])) {
            $query = mysqli_query($db, "SELECT note_id, u_id, title FROM notes WHERE u_id = ".@$user["u_id"]);
            $count = mysqli_num_rows($query);

            if($count > 0) {
                $notes = '';
                while($row = mysqli_fetch_array($query)) {
                    $notes .= '<a href="#" onclick="AddNotesPage('.$row["note_id"].'); return false;"><i class="fa fa-file-alt"></i>&nbsp;'.$row["title"].'</a>';
                }
            }else {
                $notes = '';
            }
            ?>
            <a href="#" onclick="AddNotesPage(); return false;" class="add-note text-success"><i class="fa fa-plus"></i>&nbsp;<?php echo $_lang["add_note"]; ?></a>
            <?php
            echo $notes;
        }else {
            echo '<p class="notes-login">'.$_lang["note_please_login"].'</p>';
        }
    }
}

?>