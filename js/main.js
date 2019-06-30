$(function() {
    const contentArea = $("div.access-panel");

    let isLogout = false;

    loginPanel = () => {
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "GetLoginPage"},
            success: (result) => {
                contentArea.html(result);
            }
        });
    };

    registerPanel = () => {
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "GetRegisterPage"},
            success: (result) => {
                contentArea.html(result);
            }
        });
    };

    postLogin = (_obj) => {
        let username = $("input#login-username").val();
        let password = $("input#login-password").val();
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"username": username, "password": password, "page": "TryLogin"},
            success: (result) => {
                const obj = JSON.parse(result);
                $("div.login-result").html(obj.message);
                if(obj.status == "success") {
                    reloadPage();

                    $(_obj).attr("disabled", "");
                    $(_obj).addClass("disabled");
                }
            }
        });
    };

    postRegister = (_obj) => {
        let username = $("input#register-username").val();
        let password = $("input#register-password").val();
        let repassword = $("input#register-repassword").val();

        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"username": username, "password": password, "repassword": repassword, "page": "TryRegister"},
            success: (result) => {
                const obj = JSON.parse(result);
                $("div.register-result").html(obj.message);
                if(obj.status == "success") {
                    reloadPage();

                    $(_obj).attr("disabled", "");
                    $(_obj).addClass("disabled");
                }
            }
        });
    };

    logout = () => {
        $.ajax({
            type: "POST",
            url: "/system.php",
            data: {"page": "LogoutSite"},
            success: (result) => {
                const obj = JSON.parse(result);
                $("div.access-panel").append(obj.message);
                if(obj.status == "success") {
                    reloadPage();
                }
            }
        });
    };

    reloadPage = () => {
        setInterval(() => {
            window.location.reload();
        }, 2000);
    }
});