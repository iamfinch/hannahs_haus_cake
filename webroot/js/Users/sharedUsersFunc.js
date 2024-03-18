const getStates = (
    countryId = 0,
    callback = function() {},
    errorCallback = function() {alert("there was an error")}
) => {
    if (countryId == 0 ) {
        errorCallback()
    }
    $.ajax({
        url: "/states/getStates",
        async: false,
        method: "GET",
        contentType: "application/json",
        data: {
            country_id: countryId
        },
        success: function(results) { callback(results) },
        error: function(results) { errorCallback(results) }
    });
}