$(document).ready(function () {

    $(".link").mouseover(function () {
        $(this).addClass("underline");
    });

    $(".link").mouseleave(function () {
        $(this).removeClass("underline");
    });


})

