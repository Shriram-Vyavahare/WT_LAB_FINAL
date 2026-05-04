$(document).ready(function () {

    $(".nav-link").click(function () {

        $(".nav-link").removeClass("active");
        $(this).addClass("active");

        let target = $(this).data("target");

        $(".content-box").addClass("d-none");
        $("#" + target).removeClass("d-none");
    });

});