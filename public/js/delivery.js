// $(function () {
//     $('#datetimepicker1').datetimepicker();
// });

// Show Total
// $('#deliveryChargeViewDiv').hide()

// Delivery description
// $('#deliveryDescriptionDiv').hide()

// Delivery Details Hr
// $('#deliveryDetailsHr').hide()

if(document.getElementById('address')) {
    initAutocomplete()
}

// Location autocomplete
var placeSearch, autocomplete;

var componentForm = {
    locality: 'long_name',
    country: 'long_name'
};

function initAutocomplete() {
    // Create the autocomplete object, restricting the search predictions to
    // geographical location types.
    var options = {
        componentRestrictions: {
            country: 'ke'
        }
    };

    autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), options);

    // Set bounds
    geolocate()

    // Avoid paying for data that you don't need by restricting the set of
    // place fields that are returned to just the address components.
    autocomplete.setFields(['address_component', 'geometry']);

    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    if (!place.geometry) {
        // console.log('No gometry for this place');
        toastr.warning('Please refresh page. There was an error getting location details.');
        return;
    }

    // Get current location
    var locationCoordinates = place.geometry.location.lat() + ',' + place.geometry.location.lng()

    // Update driver dropoff cordinate
    $('#dropoff_cord1').val(locationCoordinates)

    getDeliveryCosts(locationCoordinates);

    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
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
    } else {
        handleLocationError(false);
    }
}

// Geolocation error handler
function handleLocationError(browserHasGeolocation) {
    // Log error
    toastr.error(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.')
    // console.log(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
}

function getDeliveryCosts(dropOffCoordinates = null) {
    if(dropOffCoordinates) {
        // Getting post data
        var postData = {
            pickup_cord : '-1.282879,36.82150899999999',
            dropoff_cord1 : dropOffCoordinates,
            dropoff_points_count : 1,
            user_id: 48
        }

        postData['radio-vehicle'] = 'motorcycle'

        $.blockUI({
            message: '<div class="loading-message loading-message-boxed"><img src="' + assetsPath + '/images/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;Calculating delivery cost...</span></div>',
            baseZ: 1000,
            css: {
                border: '0',
                padding: '0',
                backgroundColor: 'none'
            },
            overlayCSS: {
                backgroundColor: '#555',
                opacity: 0.2,
                cursor: 'wait'
            }
        });

        // Make call to Weride API
        $.ajax({
            type: "POST",
            data: postData,
            cache: false,
            url:  deliveryUrl + "/pricing",
            dataType: "json"

        }).done(function (response) {
            $.unblockUI();
            // Converting to kilometers and minutes
            var distance_value = response.data.distance_value / 1000;
            var duration_value = response.data.duration_value / 60;

            // Updating form details
            var polyline = response.data.polyline ? response.data.polyline : '';
            var total = parseFloat(response.data.price.replace(',',''));
            var driverFee = parseFloat(response.data.driver_fee.replace(',',''));
            var serviceFee = parseFloat(response.data.service_fee.replace(',',''));
            var duration = Math.round(duration_value);
            var distance = Math.round(distance_value);
            var delivery_description = '<center><small>Delivery time: ' + duration + ' minute(s), Delivery distance: ' + distance + ' km(s)</small></center>'

            // Check if total is there 
            if (total) {
                // Edit delivery price html
                $('#deliveryChargeView').html('<strong>Ksh. ' + total + '</strong>')

                // Show Delivery Total
                $('#deliveryChargeViewDiv').show()

                // Delivery description
                $('#deliveryDescription').html(delivery_description)

                // Edit Html
                $('#deliveryDescriptionDiv').show()

                // Delivery Details Hr
                $('#deliveryDetailsHr').show()

                // Get current total
                let currentTotal = $('#total').val()
                let newTotal = parseFloat(currentTotal.replace(',','')) + total
                newTotal = newTotal 

                // Set new total
                $('#total').val(newTotal)

                // Set delivery price, distance & time
                $('#deliveryPrice').val(total)
                $('#distance').val(distance)
                $('#duration').val(duration)
                $('#driverFee').val(driverFee)
                $('#serviceFee').val(serviceFee)
                $('#polyline').val(polyline)

                // Set new total html
                $('#totalDisplay').html('<strong>Ksh. ' + newTotal + '</strong>')
                
                // Delivery details
                toastr.success('Delivery details and price added.');

            } else {
                // Show error message
                toastr.options = {
                    "closeButton": true,
                    "preventDuplicates": true,
                    "progressBar": true,
                    "timeOut": "60000"
                };

                toastr.error('There was an error encountered when making your request. Please reload the page.');
            }

        }).fail(function (error) {
            $.unblockUI();
            toastr.error('Sorry, that location could not be processed. Please try again.', 'Error');
        });

        // Continue processing
    } else {
        toastr.warning('There was an error getting your location details. Kindly input your address again.')
    }
}

// Hijack form submit
$('#orderSubmit').click(() => {
    // Make sure everything is cool before submitting
    // Checking all required variables are passed - for deliveries especially
    let dropOffCoordinates = $('#dropoff_cord1').val()

    if(dropOffCoordinates) {
        // If everything is cool
        $("#checkoutForm").submit();
    } else {
        toastr.warning('There was an error getting your location details. Kindly input your delivery address again.')
    }
})
