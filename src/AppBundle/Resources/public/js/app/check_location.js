var pinStringContainer = $(".geo-location-current-pin-string");
var latitudeCord;
var longitudeCord;

/*
  * Trazi permission od usera
*/
function getLocationFromUser() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    }
    else {
        pinStringContainer.append("Geolocation is not supported by this browser.");
    }
}

/*
  * Zapoicnje reverseLookUp
*/

function showPosition(position) {
    latitudeCord = position.coords.latitude;
    longitudeCord = position.coords.longitude;
    var reverseLookUp = "http://maps.googleapis.com/maps/api/geocode/json?latlng=";
    reverseLookUp += position.coords.latitude+","+position.coords.longitude;
    /*AJAX GET prkeo mog frameworka :3 */
    _R.sendGET(reverseLookUp, reverseLookUpByCoords, _R.log);
}


function showPositionAlt(position) {
    latitudeCord = position.coords.latitude;
    longitudeCord = position.coords.longitude;
    var reverseLookUp = "http://maps.googleapis.com/maps/api/geocode/json?latlng=";
    reverseLookUp += position.coords.latitude+","+position.coords.longitude;
    /*AJAX GET prkeo mog frameworka :3 */
    _R.sendGET(reverseLookUp, reverseLookUpByCoordsAlt, _R.log);
}

/*
  * dobijeni google json pretvara u citljivu adresicu
*/
function decodeLocationArray(results){
  city = "";
  country = "";
  for (var i=0; i<results.results[0].address_components.length; i++) {
    for (var b=0;b<results.results[0].address_components[i].types.length;b++) {
      if (results.results[0].address_components[i].types[b] == "locality") {
        if (city == "")
          city= results.results[0].address_components[i].long_name;
      }
      if (results.results[0].address_components[i].types[b] == "country") {
        if (country == "")
          country = results.results[0].address_components[i].long_name;

      }
      if (city != "" && country != ""){
        tempstr = city+", "+country;
        return tempstr;
      }
    }
  }

  if (city == "") return country;
  tempstr = city+", "+country;
  return tempstr;
}


function initGoogleMap() {
    // More info see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var mapOptions = {
        zoom: 11,
        center: new google.maps.LatLng(latitudeCord, longitudeCord),
        // Style for Google Maps
        styles: [{"featureType":"water","stylers":[{"saturation":43},{"lightness":-11},{"hue":"#0088ff"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"hue":"#ff0000"},{"saturation":-100},{"lightness":99}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#808080"},{"lightness":54}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ece2d9"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#ccdca1"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#767676"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#b8cb93"}]},{"featureType":"poi.park","stylers":[{"visibility":"on"}]},{"featureType":"poi.sports_complex","stylers":[{"visibility":"on"}]},{"featureType":"poi.medical","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","stylers":[{"visibility":"simplified"}]}]
    };

    // Get all html elements for map
    var mapElement = document.getElementById('checkUserLocationMap');

    // Create the Google Map using elements
    var map = new google.maps.Map(mapElement, mapOptions);
    var myLatLng = {lat: latitudeCord, lng: longitudeCord};
    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      draggable: true,
      title: 'Nearest'
    });
    marker.setMap(map);
    google.maps.event.addListener(marker, 'dragend', function (event) {
        var position = {
          coords : {"latitude": 0,"longitud":0}
        };
        position.coords.latitude = event.latLng.lat();
        position.coords.longitude = event.latLng.lng();
        showPositionAlt(position);
    });
}



/*
  * dobija 'near' adresicu i radi //nesto sa njom
*/
function reverseLookUpByCoords(results){
  nearest = decodeLocationArray(results);
  $('.geo-location-current-pin-string').val(nearest);
  initGoogleMap();
}

function reverseLookUpByCoordsAlt(results){
  nearest = decodeLocationArray(results);
  $('.geo-location-current-pin-string').val(nearest);
}

/*
  * Send POST na updateStatusApiEndpoint
*/

$("#updateStatusButton").click(function(){
  var statusToPost = $("#updateStatusTextarea").val();
  var locationToPost = $(".geo-location-current-pin-string").val();
  if (statusToPost.length > 0){
    var data = {
               "status": {
                   "ltd" : latitudeCord,
                   "lng" : longitudeCord,
                   "note" : statusToPost,
                   "location" : locationToPost
               }
           };
    console.log(data);
    _R.sendPOST(updateStatusApiEndpoint, data, updateStatusSuccess, _R.log);
  }
});


function updateStatusSuccess(){
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "progressBar": true,
    "preventDuplicates": false,
    "positionClass": "toast-top-full-width",
    "onclick": null,
    "showDuration": "400",
    "hideDuration": "1000",
    "timeOut": "7000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };
  toastr.success('Success!','Your status has been successfully updated! We will send an SMS message to your friends and family!');
  $("#updateStatusTextarea").val("");
}
