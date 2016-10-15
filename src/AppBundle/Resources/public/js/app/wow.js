var markers = [];
var map;

function initialize() {

  var pyrmont = new google.maps.LatLng(43.90457100430006, 20.342847634374948);
  map = new google.maps.Map(document.getElementById('mapOfWow'), {
    center: pyrmont,
    zoom: 4,
    scrollwheel: true,
    mapTypeId: google.maps.MapTypeId.TERRAIN
  });
/*

  var marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location
  });

*/
}
