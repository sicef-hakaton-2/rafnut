var markers = [];
var map;

function initialize() {
  var pyrmont = new google.maps.LatLng(LatituteLast, LongituteLast);
  map = new google.maps.Map(document.getElementById('mapOfHelpfulPlaces'), {
    center: pyrmont,
    zoom: 13,
    scrollwheel: true
  });

  var request = {
    location: pyrmont,
    radius: '5000',
    types: ['bank','atm','hospital','police','post_office','mosque'],
    key: 'AIzaSyAg3sEJRooa7jDthmCDXY9RY0EOGSTSVec'
  };

  var service = new google.maps.places.PlacesService(map);

  var infowindow = null;
  infowindow = new google.maps.InfoWindow({
    content: "//TO-DO: Dodaj pravi info"
  });


  service.nearbySearch(request, function(results, status) {
    if (status == google.maps.places.PlacesServiceStatus.OK) {
      for (var i = 0; i < results.length; i++) {

        var place = results[i];
        var icon = "";

        if (place.types[0] == "mosque"){
          icon = imgFolderEndpoint+"/img/crkva.png";
        }
        else if (place.types[0] == "bank" || place.types[0] == "finance"){
          icon = imgFolderEndpoint+"/img/money.png";
        }
        else if (place.types[0] == "atm"){
          icon = imgFolderEndpoint+"/img/atm.png";
        }
        else if (place.types[0] == "police" || place.types[0] == "local_government_office"){
          icon = imgFolderEndpoint+"/img/police.png";
        }
        else if (place.types[0] == "post_office"){
          icon = imgFolderEndpoint+"/img/post.png";
        }
        else if (place.types[0] == "hospital"){
          icon = imgFolderEndpoint+"/img/hospital.png";
        }
        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location,
          title: place.name,
          icon: icon
        });
        markers[i] = marker;
      }
      for (var i = 0; i<markers.length; i++){
        var marker = markers[i];
        google.maps.event.addListener(marker, 'click', function () {
          infowindow.setContent(this.title);
          infowindow.open(map, this);
        });
      }
    }
  });

  function showPositionAlt(LatituteLast, LongituteLast) {
      latitudeCord = LatituteLast;
      longitudeCord = LongituteLast;
      var reverseLookUp = "http://maps.googleapis.com/maps/api/geocode/json?latlng=";
      reverseLookUp += latitudeCord+","+longitudeCord;
      _R.sendGET(reverseLookUp, reverseLookUpByCoords, _R.log);
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

    function reverseLookUpByCoords(results){
      nearest = decodeLocationArray(results);
      $('#areaName').append("("+nearest+")");
    }

showPositionAlt(LatituteLast, LongituteLast);

function getLocationFromUser() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(addLocalToMap);
    }
    else {
        console.log("error");
    }
}

function addLocalToMap(position) {
    latitudeCord = position.coords.latitude;
    longitudeCord = position.coords.longitude;
    var pyrmont = new google.maps.LatLng(latitudeCord, longitudeCord);
    var marker = new google.maps.Marker({
      map: map,
      position: pyrmont,
      title: "Current location"
    });
  }

getLocationFromUser();

}
