$(document).ready(function() {
		// S'active lors du clique sur la bulle
    $('.msgbox').click(function() {
        $(this).fadeOut(400);
        $('.action-done').hide(400);
    });
    // S'active des que l'on survole la page
    $('body').hover(function(){
        $(".action-done").delay(8000).fadeOut(400).hide(400);
    });

});