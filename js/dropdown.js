$(document).ready(function() {

    $(".item").click(function() {

        if ($(".item").hasClass("is-active")) {
            $(".item").removeClass("is-active");
            $(".dropdown").removeClass("dropdown-active");
        }

        if ($(this).hasClass("item-as-dropdown")) {
            $(this).addClass("is-active");
            $(".dropdown").addClass("dropdown-active");
        }

        $(this).addClass("is-active");
    });


    var url = window.location.href;
    var exp = /^(http:\/\/)[A-Za-z0-9.:_-]*\/serrure\/([A-Za-z0-9._-]+)\.php[A-Za-z0-9?=._%&-]*$/;
    var pageName = url.replace(exp, "$2");

    if ((pageName == "list-user") || (pageName == "add-user") || (pageName == "change-code")) {
        $(".item-as-dropdown").addClass("is-active");
        $(".dropdown").addClass("dropdown-active");
    };
});