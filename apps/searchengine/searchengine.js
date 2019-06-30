$(function(){
    const googleInput = $("input#google-search-input");
    const searchInput = $("input.search-engine-hidden-input");

    searchOnGoogle = () => {
        searchInput.autocomplete({
            source: (request, response) => {
                let value = compareQuery(searchInput.val());
                $.ajax({
                    type: "POST",
                    url: "http://suggestqueries.google.com/complete/search?client=chrome&q=" + value + "&callback=callback",
                    dataType: "jsonp",
                    success: (result) => {
                        response(result[1]);
                    },
                    error: () => {
                        response(false);
                    }
                });
            },
            select: (event, ui) => {
                window.location.href = "https://google.com/search?q=" + ui.item.value + "&oq=" + compareQuery(searchInput.val());
            }
        });

        searchInput.data('ui-autocomplete')._renderItem = (ul, item) => {
            let li = $('<li>');
            li.html('<a href="#" onclick="return false;"><i class="fa fa-search"></i>&nbsp;' + item.value + '</a>');
            return li.appendTo(ul);
        };
    };

    if(googleInput.length > 0) {
        searchInput.on("change paste keyup", () => {
            if(searchInput.val() != "") {
                searchOnGoogle();
            }
        });

        document.addEventListener("keyup", (e) => {
            if(e.keyCode == 13) {
                if(!searchInput.val() == "") {
                    let value = compareQuery(searchInput.val());
                    window.location.href = "https://google.com/search?q=" + value + "&oq=" + value;
                }
            }
        });
    }

    compareQuery = (value) => {
        return value.replace(/ /g, '+');
    }
});