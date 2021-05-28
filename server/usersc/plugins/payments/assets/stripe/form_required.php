<?php if(count(get_included_files()) ==1) die();
if(haltPayment('stripe')){die("This form of payment is disabled");}
//This is the required part of the form. You may add additional form fields as necessary
if(!isset($formInfo)){
  die("The formInfo variable is required.  Please see documentation for an explaination.");
}
?>




<form class="" action="<?=$formInfo['action'];?>" method="post" id="payment-form">
  <input type="hidden" name="processPayment" value="1">
<br>
<span class="payment-errors"></span>
<div class="form-row">
<label class="text-left">
  <span>Full name (on the cards)</span>
  <input class="form-control" type="text" size="50" name="fullname" value="" id="fullName"  placeholder="Full Name" required />
</label>
</div>

<div class="form-row"> 
  <label class="text-left">
    <span> Card number</span>
    <div class="input-group"> 
    <input class="form-control" type="text" size="50" data-stripe="number" value="" id="account" placeholder="Your card number" required />
     <div class="input-group-append">
        <span class="input-group-text text-muted">
            <i class="fa fa-credit-card mx-1"></i>
       </span>
      </div>
    </div>
  </label>
</div>
  <div class="form-row">
      <div class="col-sm-8">
        <div class="form-group text-left">
            <label class="text-left"><span class="hidden-xs ">Expiration</span></label>
            <div class="input-group">
                <input type="number" placeholder="MM" name="" class="form-control"  data-stripe="exp-month" id="expMonth" value=""  required>
                <input type="number" placeholder="YY" name="" class="form-control" data-stripe="exp-year" value="" id="expYear"  required>
            </div>
        </div>
       </div>
       
       <div class="col-sm-4">
          <div class="form-group mb-4 text-left">
             <label class="text-left" data-toggle="tooltip" title="Three-digits code on the back of your card">CVV
                   <i class="fa fa-question-circle"></i>
             </label>
            <input type="text" data-stripe="cvc" value=""  required class="form-control">
          </div>
       </div>
     </div>
     
     
     
     <!-- this is the new code for stripe apple/google pay. -->
     <div class="form-row">
         <div class="col-sm-12">

<!-- vvv  all the code needed is this vvv -- >	
             <div id="payment-request-button">
        <!-- A Stripe Element will be inserted here if the browser supports this type of payment method. -->
             </div>
<!-- ^^^  all the code needed is this.^^^ -->


      	    <!-- this code is not needed, for troubleshooting. can delete -- >
              <div id="messages" role="alert" style="display: none;"></div>
	    <!-- code up ^ can be deleted -->
      </div>
     </div>

	<!-- code ends here -->
      
