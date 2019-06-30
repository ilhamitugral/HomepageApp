$(function(){
    const statusArea = $("div.settings-status");
    const contentArea = $("div.settings-content-area");
    const navLinks = $("ul.navigation li a");

    let username = undefined;
    let name = undefined;
    let surname = undefined;
    let email = undefined;
    let old_pass = undefined;
    let new_pass = undefined;
    let re_pass = undefined;
    let language = undefined;
    let theme = undefined;

    getUserInfoPage = (obj) => {
        if(!$(obj).hasClass("active")) {
            statusArea.html("");
            $.ajax({
                type: "POST",
                url: "/system.php",
                data: {"page": "GetSettingsPage"},
                success: (result) => {
                    contentArea.html(result);
                }
            });
        }
    };

    saveSettings = () => {
        username = $("input#settings-username");
        name = $("input#settings-name");
        surname = $("input#settings-surname");
        email = $("input#settings-email");
        old_pass = $("input#settings-old-password");
        new_pass = $("input#settings-new-password");
        re_pass = $("input#settings-repassword");
        language =   $("select#settings-language");
        theme = $("select#settings-theme");

        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {
                "page": "SaveSettings",
                "username": username.val(),
                "name": name.val(),
                "surname": surname.val(),
                "email": email.val(),
                "old_password": old_pass.val(),
                "new_password": new_pass.val(),
                "re_password": re_pass.val(),
                "language": language.val(),
                "theme": theme.val()
            },
            success: (result) => {
                const data = JSON.parse(result);
                if(data.status == "success") {
                    setInterval(() => {
                        window.location.reload();
                    }, 2000);
                }
            }
        });
    };

    getAppsInfoPage = (obj) => {
        if(!$(obj).hasClass("active")) {
           showAppInfo();
        }
    };

    showAppInfo = () => {
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "GetAppsSettings"},
            success: (result) => {
                contentArea.html(result);
            }
        });
    }

    changeActiveButton = (obj) => {
        if(!$(obj).hasClass("active")) {
            navLinks.removeClass("active");
            $(obj).addClass("active");
        }
    }

    getAppSettings = (page) => {
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "AppSettings", "settings": page},
            success: (result) => {
                contentArea.html(result);
            }
        })
    }

    returnApps = () => {
        showAppInfo();
    }

    getUserInfoPage();
});