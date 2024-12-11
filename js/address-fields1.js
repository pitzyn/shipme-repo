function fillInAddress() {
      // Get the place details from the autocomplete object.
      var place = autocomplete.getPlace();
      var lat = place.geometry.location.lat();
      var lng = place.geometry.location.lng();


      document.getElementById('start_lat').value = lat;
      document.getElementById('start_lon').value = lng;

    }



    function fillInAddress2() {
          // Get the place details from the autocomplete object.
          var place = autocomplete2.getPlace();
          var lat = place.geometry.location.lat();
          var lng = place.geometry.location.lng();


          document.getElementById('end_lat').value = lat;
          document.getElementById('end_lon').value = lng;

        }


function initAutocomplete()
{
     // Create the autocomplete object, restricting the search to geographical
     // location types.
     autocomplete = new google.maps.places.Autocomplete(
       /** @type {!HTMLInputElement} */(document.getElementById('start_location')),
       {types: ['geocode']});

     // When the user selects an address from the dropdown, populate the address
     // fields in the form.
     autocomplete.addListener('place_changed', fillInAddress);


     autocomplete2 = new google.maps.places.Autocomplete(
       /** @type {!HTMLInputElement} */(document.getElementById('end_location')),
       {types: ['geocode']});

     // When the user selects an address from the dropdown, populate the address
     // fields in the form.
     autocomplete2.addListener('place_changed', fillInAddress2);

}


jQuery(document).ready(function (){

initAutocomplete();   
jQuery( "#date_start" ).datepicker({ dateFormat: 'yy-mm-dd' });
jQuery( "#date_end" ).datepicker({ dateFormat: 'yy-mm-dd' });

});
