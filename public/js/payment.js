$(document).ready(function() {
    // Check if mpesa or cash
    let paymentMethod = $("input[name='payment_type']:checked").val();

    if(paymentMethod) {
        paymentMethodDetailsProcess(paymentMethod)
    } else {
        paymentMethodDetailsProcess('mpesa')
    }

    // Disable complete button
    $('#paymentPhoneNumber').val() ? '' : $("#paymentSubmit").prop('disabled', true);

    // On payment method change
    $("input[name=payment_type]").change(function() {
        paymentMethodDetailsProcess(this.value)
    })

    // On phone number input
    $("#paymentPhoneNumber").keyup(function() {
        // paymentMethodDetailsProcess(this.value)
        $("#paymentSubmit").prop('disabled', false);

        let paymentMethod = $('input[name=payment_type]').val()
        // console.log(paymentMethod)
    })

    function paymentMethodDetailsProcess(paymentMethod = null) {
        if(paymentMethod) {
            // Show mpesa details
            if(paymentMethod === 'mpesa') {
                // Show mpesa content
                $('#mpesaContent').show()
                $('#cashContent').hide()
                $('#paymentPhoneNumber').val() ? '' : $("#paymentSubmit").prop('disabled', true);
            
            } else if (paymentMethod === 'cash') {
                // Show card content
                $('#mpesaContent').hide()
                $('#cashContent').show()
                $("#paymentSubmit").prop('disabled', false);
            
            } else {
            
            }
        }
    }

    $("#paymentForm").submit(function(e){
        e.preventDefault();
    });

    $("#paymentSubmit").click(function () {
        // console.log($("#paymentForm").serialize())

        // Check payment type
        let paymentMethod = $('input[name=payment_type]:checked').val()
        let isFormValid = document.getElementById('paymentForm').checkValidity()
        // console.log('Is form valid: ' + isFormValid)

        if(isFormValid) {
            // console.log(paymentMethod)
            if(paymentMethod === "cash") {
                // Submit form
                document.getElementById("paymentForm").submit();
            } else if(paymentMethod === "mpesa") {
                // Block UI
                $.blockUI({
                    message: '<div class="loading-message loading-message-boxed"><img src="' + assetsPath + '/images/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;Processing Mpesa Payment...</span></div>',
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

                // Post transaction
                $.ajax({
                    type: "POST",
                    data: $('#paymentForm').serialize(),
                    cache: false, // TODO: Use endpoint from the API
                    url: site_url + "/pay/initiate", // Works and returns a JSON object
                    dataType: "json"

                }).done(function(response) {
                    // console.log(response)
                    $.unblockUI();
                    // console.log('Success: ', response);
                    // console.log('Payment type: ', response.payment_type);

                    // Payment initiated
                    toastr.info('Processing...');

                    if(response.payment_type == 'mpesa'){
                        // Display mpesa message
                        // console.log('Mpesa header status: ', response.status);
                        // console.log('Mpesa text: ', response.data);
                        // Check for payment completion
                        // Call method

                        // Update mpesa description text to show account details in case the STK Push does not work
                        if(response.data && response.data.account && response.data.paybill) {
                            var ipay_paybill = response.data.paybill;
                            var ipay_account = response.data.account;
                            // If both items are present in the response, add data to mpesa payment form
                            document.getElementById("mpesa-extra-details").innerHTML = '<div class="alert alert-success"><span><h4 style="color: black;">Payment info</h4>If you don\'t receive a notification on your phone, use the process below to pay for the order:<br><br> 1. Go to the M-PESA Menu <br> 2. Select Lipa na M-PESA <br> 3. Select Pay Bill <br> 4. Enter Business No. <strong><span style="color: black;">' + ipay_paybill + '</span></strong> <br> 5. Enter Account No. <strong><span style="color: black;">' + ipay_account + '</span></strong> <br> 6. Enter <strong>KES.<span style="color: black;"> ' + response.amount + '</span></strong><br> 7. Enter your M-Pesa PIN then send  </span></div>';
                        }

                        if(response.status === "success"){
                            // Payment initiated
                            $('#paymentSubmit').removeClass('btn-primary');
                            $('#paymentSubmit').addClass('btn-warning');

                            toastr.success('Check your phone to finalize M-pesa payment');

                            document.getElementById("paymentSubmit").innerHTML = '<span class="description"><i class="icon-ok-circled2-1"></i> Payment initiated. Check your phone.</span><span class="success"><svg x="0px" y="0px" viewBox="0 0 32 32"><path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"/></svg></span>';

                            if(!$('#search-payment').length) {
                                $('#paymentSubmit').fadeOut(function() {
                                    $('#paymentSubmit').after('<button type="button" id="search-payment" onClick="searchPayment()" class="utility-box-btn btn btn-info btn-block btn-lg btn-submit">Confirm payment</button>');
                                });
                            }

                            // console.log('Search process done');

                        } else {
                            // console.log('Error on mpesa');

                            // Error 
                            toastr.error('Error encountered. Please refresh the page and try again.');
                        }

                    } else {
                        toastr.error('There was an error processing your payment. Unknown payment method. Kindly try again or try another payment method. If the issue persists, kindly contact support.')
                    }
                    
                }).fail(function(error) {
                    $.unblockUI();
                    // console.log('Error: ', error);
                    // Sorry, an error was encountered
                    toastr.error('There was an error processing your payment. Kindly try again or try another payment method. If the issue persists, kindly contact support.')
                });

            } else {
                // Throw error
                toastr.error('Unknown payment type')
            }
        } else {
            if(paymentMethod === "cash") {
                document.getElementById("paymentForm").submit();
            } else {
                toastr.error('Please enter a valid phone number.')
            }
        }
    });
})

function searchPayment(){    
    toastr.info('Checking payment status...')

    $.blockUI({
        message: '<div class="loading-message loading-message-boxed"><img src="' + assetsPath + '/images/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;Processing...</span></div>',
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

    $.ajax({
        type: "POST",
        data: $('#paymentForm').serialize(),
        cache: false, // TODO: Use endpoint from the API
        url: site_url + "/pay/search", // Works and returns a JSON object
        dataType: "json"

    }).done(function(response) {
        $.unblockUI();
        // Check if payment is complete/pending
        // If complete, Run completion process i.e send email etc
        // console.log('Response: ', response);

        if(response.status === 'success') {
            // If status == success
            toastr.success('Payment successfully received! Redirecting you...')

            // $("#paymentForm").submit();
            document.getElementById("paymentForm").submit();

        } else {
            // IF NOT complete, try again option
            if(response.data.payment_status === "less") {
                toastr.error('You payed less than the amount required. Please pay the full amount or contact support at support@kilimanjarofood.co.ke.')

            } else {
                toastr.error('Payment not received. Please try again in a moment.')
            }
        }

    }).fail(function(error) {
        $.unblockUI();
        // IF NOT complete, try again option
        if(error.responseJSON.data.payment_status === "less") {
            toastr.error('You payed less than the amount required. Please pay the full amount or contact support at support@kilimanjarofood.co.ke.')

        } else {
            toastr.error('Payment not received. Please try again in a moment.')
        }
    });
}
