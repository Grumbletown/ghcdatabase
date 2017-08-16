//add active class to navbar elements
function setNavElemActive(elementIDs) {
    //removes active class of every nav element
    $("nav .active").each(function(i, e) {
        e.removeClass("active");
    });
    //ads active class to selected elements
    $.each(elementIDs, function(i, e) {
        $("#" + e).addClass("active");
    });
};