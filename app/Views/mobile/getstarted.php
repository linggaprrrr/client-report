<?= $this->extend('mobile/layout/template') ?>
<?= $this->section('content') ?>
<style>

</style>
<div class="content">
    <div class="card">
        <div class="card-body">
            <span>
                <p class="text-justify">
                    <img src="/assets/images/fba-logo.png" class="d-none d-sm-block" alt="FBA Logo" style="margin-bottom: 20px;">Hello and Welcome to the Smart FBA family! We are happy and grateful that you made the decision to work with us and our dedicated team. We have over 40 team members working hard to make sure your experience is a great one.
                </p>
                <p>Below is a list of a few of the things we need you to do to get the process started.</p>
                <p class="text-justify">Before you do any purchase of inventory or brand approvals we <b>HIGHLY</b> recommend watching this video about the process and expectations for this business. We are <b>NOT</b> a get rich quick scheme nor are we here to promise the unrealistic. You will experience good profitable months and other months may not be. This is the nature of business especially with Amazon. Building an Amazon FBA store takes time and patience. </p>
                <br>
                <a href="https://www.youtube.com/watch?v=SbyUfvZDqoU" target="_blank">Click Here</a> to watch the Video Now</P>
                <br>
                <p>Once you watch the video, please follow these next steps. </p>
                <ol type="1">
                    <li>
                        <p>Make sure to connect with your Customer Service team thru Facebook messenger. Nikki will be your account manager for any questions you may have, but any of our team members are more than happy to assist you. They will always be your point of contact for any questions about your store. </p>
                    </li>
                    <li>
                        <p>We need you to set up your $150/month recurring payment. This payment will begin 30 days after you set up the payment. Please note this step needs to be done PRIOR to purchasing inventory. (<a href="https://square.link/u/KYEuq1Hy" target="_blank">https://square.link/u/KYEuq1Hy</a>)</p>
                    </li>
                    <li>
                        <p>It's time to purchase inventory! Please remember to spread your merchandise budget out over the first 4-6 months. DO NOT spend it all at once. Amazon rewards activity and consistency in your store. They first need to trust you, and they need to see consistent creation or replenishment of inventory in your store. Placing one order and sitting and waiting until that order completely sells out is not advisable. Your store will not perform well. (<a href="https://checkout.square.site/merchant/MLZMCABT6VC84/checkout/53ERSRX7XUBPWNWGNIGNDVEM" target="_blank">https://checkout.square.site/merchant/MLZMCABT6VC84/checkout/53ERSRX7XUBPWNWGNIGNDVEM</a>)</p>
                    </li>
                    <li>
                        <p>Brand Approvals! This is a great way to get ahead of the competition! Please check out our Brand Approvals link to start. (<a href="<?= base_url('/brand-approvals') ?>">Brand Approvals</a>)</p>
                    </li>
                </ol>
                <br>
                <br>
                <p>Thank you</p>

            </span>
        </div>

    </div>
    <button type="button" id="pnotify-info" style="display: none;"></button>
    <button type="button" id="pnotify-info-costleft" style="display: none;"></button>
    <button type="button" id="pnotify-info-month" style="display: none;"></button>

    <!-- /blocks with chart -->
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<?= $monthDiff->monthdiff ?>
<script>
    $(document).ready(function() {
        
      
        <?php if ($costLeft < 100 && $monthDiff->monthdiff > 2) : ?>
            $('#pnotify-info').click();
        <?php elseif ($costLeft < 100) : ?>
            $('#pnotify-info-costleft').click();
        <?php elseif ($$monthDiff->monthdiff > 2) : ?>
            $('#pnotify-info-month').click();
        <?php endif ?>
    })

    // $('#pnotify-info').on('click', function() {
    //     swal("Manifest is more than 2 months and Almost Completed", "Time to re-order, please go to Purchase Inventory to order more", "info");
    // });
 
    // $('#pnotify-info-costleft').on('click', function() {
    //     swal("Manifest is Almost Completed", "Time to re-order, please go to Purchase Inventory to order more", "info");
        
    // });
    // $('#pnotify-info-month').on('click', function() {
    //     swal("Manifest is more than 2 months", "Time to re-order, please go to Purchase Inventory to order more", "info");        
    // });
</script>
<?= $this->endSection() ?>