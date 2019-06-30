$(function(){
    const statusArea = $("div.settings-status");

    saveNews = () => {
        const source = $("select#news-settings-source").val();

        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "saveNewsSettings", "saveSettings": true, "news": source},
            success: (result) => {
                const obj = JSON.parse(result);
                statusArea.html(obj.message);
                if(obj.status == "success") {
                    setInterval(() => {
                        window.location.reload();
                    }, 2000);
                }
            }
        });
    }
});