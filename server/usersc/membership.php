<?php
ini_set('max_execution_time', 1356);
ini_set('memory_limit','1024M');
?>
<?php
require_once '../users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if(isset($user) && $user->isLoggedIn()){
}

$page = "Membership";
$pn_page = $page;



// stripe for apple pay
require_once 'shared.php';

try {
  $paymentIntent = $stripe->paymentIntents->create([
    'payment_method_types' => ['card'],
    'amount' => 100, // will be changed to variable tommorrow $amount or something like that 
    'currency' => 'usd',
  ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
  http_response_code(400);
  error_log($e->getError()->message);
?>
  <h1>Error</h1>
  <p>Failed to create a PaymentIntent</p>
  <p>Please check the server logs for more information</p>
<?php
  exit;
} catch (Exception $e) {
  error_log($e);
  http_response_code(500);
  exit;
}
?> 
<link rel="stylesheet" href="css/base.css" />
    <script src="https://js.stripe.com/v3/"></script>
    <script src="utils.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', async () => {
        // 1. Initialize Stripe
        const stripe = Stripe('<?= $_ENV["STRIPE_PUBLISHABLE_KEY"]; ?>', {
          apiVersion: '2020-08-27',
        });

        // 2. Create a payment request object
        var paymentRequest = stripe.paymentRequest({
          country: 'US',
          currency: 'usd',
          total: {
            label: 'Demo total',
            amount: 100, // same thing variable to $amount or something. its also done in php so customer cant edit it. 
          },
          requestPayerName: true,
          requestPayerEmail: true,
        });

        // 3. Create a PaymentRequestButton element
        const elements = stripe.elements();
        const prButton = elements.create('paymentRequestButton', {
          paymentRequest: paymentRequest,
        });

        // Check the availability of the Payment Request API,
        // then mount the PaymentRequestButton
        paymentRequest.canMakePayment().then(function (result) {
          if (result) {
            prButton.mount('#payment-request-button');
          } else {
            document.getElementById('payment-request-button').style.display = 'none';
            addMessage('Apple Pay support not found. Check the pre-requisites above and ensure you are testing in a supported browser.');
          }
        });

        paymentRequest.on('paymentmethod', async (e) => {
          // Make a call to the server to create a new
          // payment intent and store its client_secret.
          addMessage(`Client secret returned.`);
          let clientSecret = '<?= $paymentIntent->client_secret; ?>';

          // Confirm the PaymentIntent without handling potential next actions (yet).
          let {error, paymentIntent} = await stripe.confirmCardPayment(
            clientSecret,
            {
              payment_method: e.paymentMethod.id,
            },
            {
              handleActions: false,
            }
          );

          if (error) {
            addMessage(error.message);

            // Report to the browser that the payment failed, prompting it to
            // re-show the payment interface, or show an error message and close
            // the payment interface.
            e.complete('fail');
            return;
          }
          // Report to the browser that the confirmation was successful, prompting
          // it to close the browser payment method collection interface.
          e.complete('success');

          // Check if the PaymentIntent requires any actions and if so let Stripe.js
          // handle the flow. If using an API version older than "2019-02-11" instead
          // instead check for: `paymentIntent.status === "requires_source_action"`.
          if (paymentIntent.status === 'requires_action') {
            // Let Stripe.js handle the rest of the payment flow.
            let {error, paymentIntent} = await stripe.confirmCardPayment(
              clientSecret
            );
            if (error) {
              // The payment failed -- ask your customer for a new payment method.
              addMessage(error.message);
              return;
            }
            addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
            
          }
            //The payment was successful! Do something here basically.
          addMessage(`Payment ${paymentIntent.status}: ${paymentIntent.id}`);
          if (paymentIntent.status === 'succeeded') {
             //do stuff here ahahahaha
          }
        });

      });
    </script>
    <!-- stripe end -->



<!-- Begin Page Content -->
<div class="container-fluid">

<?php
$page= "Membership";
?>
<title><?= (($pageTitle != '') ? $pageTitle : ''); ?> <?=$settings->site_name?></title>
<?php $view1 = Input::get('view');?>
<?php 
if($view1 == '' || $view1 == 'users'){
if((time() - strtotime($settings->announce)) > 10800){
$db->update('settings',1,['announce'=>date("Y-m-d H:i:s")]);
}
}
?>
      
</div></div> 


  <!-- ======= Hero Section ======= -->
  
  <main id="main">
 <section  class="breadcrumbs">

    <!-- ======= Dash ======= -->
    <section id="services" class="services section-bg">
      <div class="container" data-aos="fade-down">
        <header class="section-header">
          <h3><?php echo $settings->site_name;?> | <?=$page?></h3><br />
        </header>
    
    
    <!-- Begin Page Content -->
    <div class="container-fluid">
        

</header>


<div class="container">
<div id="right-panel" class="right-panel">

  <div id="messages" class="sufee-alert alert with-close alert-primary alert-dismissible fade show d-none">
    <span id="message"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <?php
   include($abs_us_root.$us_url_root.'usersc/plugins/membership/hooks/_membership.php');
   $pn_success = Input::get('display');
   if($pn_success == "success"){
    include($abs_us_root.$us_url_root.'usersc/plugins/membership/hooks/_success.php');
   }
  ?>
  </div> <!-- .content -->
</div><!-- /#right-panel -->
 </section>
    <!-- ======= Call To Action Section ======= -->
    <section id="call-to-action" class="call-to-action">
      <div class="container" data-aos="zoom-out">
            <center><h3 class="cta-title "><i class="fab fa-1x  fa-expeditedssl"></i> Safe Checkout</h3>
            <p class="cta-title " ><i class="fab fa-1x fa-cc-stripe"></i> <i class="fab  fa-1x  fa-cc-visa"></i> <i class="fab  fa-1x fa-cc-discover"></i> <i class="fab fa-1x  fa-cc-amex"></i> <i class="fab fa-1x  fa-cc-mastercard"></i></p></center>
      </div>
    </section><!--  End Call To Action Section -->


          <script type="text/javascript">
      $(document).ready(function(){
        var x_timer;
        $("#username").keyup(function (e){
          clearTimeout(x_timer);
          var username = $(this).val();
          if (username.length > 0) {
            x_timer = setTimeout(function(){
              check_username_ajax(username);
            }, 500);
          }
          else $('#usernameCheck').text('');
        });

        function check_username_ajax(username){
          $("#usernameCheck").html('Checking...');
          $.post('parsers/existingUsernameCheck.php', {'username': username}, function(response) {
            if (response == 'error') $('#usernameCheck').html('There was an error while checking the username.');
            else if (response == 'taken') { $('#usernameCheck').html('<i class="fa fa-times" style="color: red; font-size: 12px"></i> This username is taken.');
            $('#addUser').prop('disabled', true); }
            else if (response == 'valid') { $('#usernameCheck').html('<i class="fa fa-thumbs-o-up" style="color: green; font-size: 12px"></i> This username is not taken.');
            $('#addUser').prop('disabled', false); }
            else { $('#usernameCheck').html('');
            $('#addUser').prop('disabled', false); }
          });
        }
      });
      </script>
        
        </div>
        <!-- /.container-fluid -->
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
