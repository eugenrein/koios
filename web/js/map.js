$(function() {
    window.showMap = function($element) {
        var map = new google.maps.Map($element[0], {
            zoom: 4,
            center: {lat: 37.977778, lng: 23.727778}
        });
        console.log($element.data('kml'));
        
        var overlay = new google.maps.KmlLayer({
            url: $element.data('kml'),
            map: map
        });
    };
});