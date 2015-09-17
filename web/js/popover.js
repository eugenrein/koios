$(function () { 
    $("[data-toggle='popover']")
        .popover()
        .on('shown.bs.popover', function() {
            $('.popover-map').each(function() {
                if ($(this).children().length <= 0) {
                    window.showMap($(this));
                }
            })
        }); 
});