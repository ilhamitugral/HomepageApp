$(function() {
    const dragPanel = $("div.drag-panel-area");
    const draggableArea = "div.draggable-area";

    getCustomizePanel = () => {
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "GetCustomizePanel"},
            success: (result) => {
                dragPanel.html(result);
            }
        });
        sortableCreatePanel();
    };

    sortableCreatePanel = () => {
        // Uygulamalar'dan sürüklenebilen panel.
        dragPanel.sortable({
            revert: true,
            connectWith: [draggableArea],
            cursor: "grab",
            forcePlaceholderSize: true,
            update: (e, ui) => {
                if($(ui.item).parent()[0].classList[0] == "draggable-area") {
                    $.ajax({
                        type: "POST",
                        url: "/system.php",
                        data: {"page": "UpdateDesign", "data": renderTemplate()},
                        success: (result) => {
                            if(result == "OK") {
                                getCustomizePanelPage(ui.item);
                            }else {
                                alert(result);
                            }
                        }
                    });
                }
            }
        }).disableSelection();
    };

    getCustomizePanelPage = (obj) => {
        // Uygulamayı ekliyor.
        data = obj.attr("data-name");
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "GetCustomizePanelPage", "data": data},
            success: (result) => {
                $(obj).html(result);
            }
        });
    }

    renderTemplate = () => {
        let userDesign = "";
        // Sortable Panel loop
        for(i = 0; i < $(draggableArea).length; i++) {
            userDesign += "[";
            panel = $(draggableArea)[i];

            for(j = 0; j < panel.children.length; j++) {
                item = panel.children[j].attributes[1].nodeValue;
                userDesign += "{" + item + "}";
            }

            userDesign += "]";
        }
        return userDesign;
    }

    $(draggableArea).sortable({
        revert: true,
        connectWith: [draggableArea, dragPanel],
        cursor: "grab",
        handle: "span.title",
        forcePlaceholderSize: true,
        start: (e, ui) => {
            let value = ui.item.css("width");
            value = parseInt(value.substr(0, value.length - 2));
            
            if(value > 300) {
                ui.item.css("width", 300);
            }
        },
        update: (e, ui) => {
            if($(ui.item).parent()[0].classList[1] == "drag-panel-area") {
                // Kaldırma bölümü
                $.ajax({
                    type: "POST",
                    url: "/system.php",
                    data: {"page": "UpdateDesign", "data": renderTemplate()},
                    success: (result) => {
                        if(result == "OK") {
                            $(ui.item).remove();
                            getCustomizePanel();
                        }else {
                            alert(result);
                        }
                    }
                });
            }else {
                $.ajax({
                    type: "POST",
                    url: "/system.php",
                    data: {"page": "UpdateDesign", "data": renderTemplate()},
                    success: (result) => {
                        if(!result == "OK") {
                            alert(result);
                        }
                    }
                });
            }
        }
    });
    getCustomizePanel();

    $("section.search-engine").addClass("disabled");
});