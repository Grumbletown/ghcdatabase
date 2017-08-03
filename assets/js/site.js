//add active class to open navbar elements
$(document).ready(function() {
    var url = window.location.pathname;
    var filename = url.substring(url.lastIndexOf('/') + 1);

    try {
        document.getElementById(filename).classList.add("active");
    } catch (e) {

    }
});