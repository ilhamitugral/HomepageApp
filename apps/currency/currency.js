$(function() {
    const hostName = $("span.site-name").text();
    const statusArea = $("div.settings-status");
    let currencyList = $("span.currency-list").text();
    let currency = [];
    currencyList = currencyList.split(",");

    saveCurrency = () => {
        for(let i = 0; i < currencyList.length; i++) {
            currency[i] = $("input#currency_" + currencyList[i] + ":checked").length;
        }
        
        $.ajax({
            type: "POST",
            url: hostName + "/system.php",
            data: {"page": "saveCurrencySettings", "save_settings": true, "data": currency.join()},
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