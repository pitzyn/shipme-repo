jQuery( document ).ready(function() {
  jQuery('#pickup_date').pickadate( { min: new Date(),  onSet: function(thingSet) {
     jQuery("#pickup_date_hidden").val(thingSet.select/1000 + 12400);

} });
  jQuery('#delivery_date').pickadate( { min: new Date() ,  onSet: function(thingSet) {
     jQuery("#delivery_date_hidden").val(thingSet.select/1000 + 12400);
}   });
});

// This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var placeSearch, autocomplete, autocomplete2;


function initAutocomplete() {
  // Create the autocomplete object, restricting the search to geographical
  // location types.
  autocomplete = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete_pickup')),
    {types: ['geocode']});

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete.addListener('place_changed', fillInAddress);


  //-------------------------------------------------------------------

  autocomplete2 = new google.maps.places.Autocomplete(
    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete_delivery')),
    {types: ['geocode']});

  // When the user selects an address from the dropdown, populate the address
  // fields in the form.
  autocomplete2.addListener('place_changed', fillInAddress2);


}

// [START region_fillform]
function fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = autocomplete.getPlace();
  var lat = place.geometry.location.lat();
  var lng = place.geometry.location.lng();

  console.log("test")
  document.getElementById('pickup_lat').value = lat;
  document.getElementById('pickup_lng').value = lng;

}

function fillInAddress2() {
  // Get the place details from the autocomplete object.
  var place = autocomplete2.getPlace();
  var lat = place.geometry.location.lat();
  var lng = place.geometry.location.lng();


  document.getElementById('delivery_lat').value = lat;
  document.getElementById('delivery_lng').value = lng;

}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate_pickup() {
  if (navigator.geolocation) {



  navigator.geolocation.getCurrentPosition(function(position) {
    var geolocation = {
    lat: position.coords.latitude,
    lng: position.coords.longitude
    };
    var circle = new google.maps.Circle({
    center: geolocation,
    radius: position.coords.accuracy
    });
    autocomplete.setBounds(circle.getBounds());

    console.log(position.coords.latitude)

  });
  }
}



function geolocate_delivery() {
  if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    var geolocation = {
    lat: position.coords.latitude,
    lng: position.coords.longitude
    };
    var circle = new google.maps.Circle({
    center: geolocation,
    radius: position.coords.accuracy
    });
    autocomplete.setBounds(circle.getBounds());
  });
  }
}
