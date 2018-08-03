<?php
  require_once("Functions.php");
  start($pdo);

?>
<script type="text/javascript" src="https://js.squareup.com/v2/paymentform">
  $(document).ready(function(event){

    //Stolen from: https://docs.connect.squareup.com/payments/sqpaymentform/setup
    // Set the application ID
    var applicationId = "";

    // Set the location ID
    var locationId = "";

    //function: requestCardNonce Triggered when paying with credit card
    function requestCardNonce(event) {
    // Request a nonce from the SqPaymentForm object
    paymentForm.requestCardNonce();
    }

    // Create and initialize a payment form object
    var paymentForm = new SqPaymentForm({

      // Initialize the payment form elements
      applicationId: applicationId,
      locationId: locationId,
      inputClass: 'sq-input',

      // Initialize the credit card placeholders
      cardNumber: {
        elementId: 'sq-card-number',
        placeholder: <?=$_POST['card_number']; ?>
      },
      cvv: {
        elementId: 'sq-cvv',
        placeholder: <?=$_POST['cvv']; ?>
      },
      expirationDate: {
        elementId: 'sq-expiration-date',
        placeholder: <?=$_POST['expiration_date'];?>
      },
      postalCode: {
        elementId: 'sq-postal-code'
        placeholder: <?=$_POST['zip_code'];?>
      },

      // SqPaymentForm callback functions
      callbacks: {
        /*
        * callback function: cardNonceResponseReceived
        * Triggered when: SqPaymentForm completes a card nonce request
        */
        cardNonceResponseReceived: function(errors, nonce, cardData, billingContact, shippingContact) {
          if (errors) {
            // Log errors from nonce generation to the Javascript console**
            console.log("Encountered errors:");
            errors.forEach(function(error) {
              console.log('  ' + error.message);
            });

            return;
          }
          alert('Nonce received: ' + nonce); /* FOR TESTING ONLY */
          // Send the nonce to a page that enters it into the database
          $.ajax({
            type: 'post',
            url: 'Store.php',
            datatype: 'json',
            data: JSON.stringify({
              'nonce':nonce,
              'order_id':<?=$_POST['people_id'] ?>
            }),
            success:function(data)
            {
              console.log('succeeded in sending info to Store');
            }
          })
        }
      }
    });
  })
</script>
