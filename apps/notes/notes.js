$(function(){

    const notesContent = $("div.notes");

    AddNotesPage = (id) => {
        $.ajax({
            type: "POST",
            url: "/apps/notes/index.php",
            data: {"page": "AddNotes", "id": id},
            success: (result) => {
                notesContent.html(result);
            }
        });
    };

    ShowNotesPage = () => {
        $.ajax({
            type: "POST",
            url: "/apps/notes/index.php",
            data: {"page": "ShowNotes"},
            success: (result) => {
                notesContent.html(result);
            }
        });
    };

    AddNote = () => {
        let noteTitle = $("input#notes-title").val();
        let noteDescription = $("div#notes-description").html();
        $.ajax({
            type: "POST",
            url: "/apps/notes/index.php",
            data: {"page": "NewNotes", "title": noteTitle, "description": noteDescription},
            success: (result) => {
                const data = JSON.parse(result);
                if(data.status == "success") {
                    ShowNotesPage();
                }
            }
        });
    };

    UpdateNote = (id) => {
        let noteTitle = $("input#notes-title").val();
        let noteDescription = $("div#notes-description").html();
        $.ajax({
            type: "POST",
            url: "/apps/notes/index.php",
            data: {"page": "UpdateNotes", "title": noteTitle, "description": noteDescription, "id": id},
            success: (result) => {
                const data = JSON.parse(result);
                if(data.status == "success") {
                    ShowNotesPage();
                }
            }
        });
    };

    DeleteNote = (id) => {
        $.ajax({
            type: "POST",
            url: "/apps/notes/index.php",
            data: {"page": "DeleteNote", "id": id},
            success: (result) => {
                const data = JSON.parse(result);
                if(data.status == "success") {
                    ShowNotesPage();
                }
            }
        });
    }

    textAreaCheck = (e) => {
        let keyCode = e.which || e.keyCode;
        if(keyCode == 13) {
            $('div.note-text div.description').focus();
            return false;
        }
    }
});