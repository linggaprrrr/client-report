<?= $this->extend('mobile/layout/template') ?>

<?= $this->section('content') ?>
<style>
    .bank-account {
        font-family: roboto;
        font-size: 18px;
        font-weight: bold;

    }
</style>
<div class="content">
    <div class="card">
        <div class="card-header">
             <object >
                <param name="movie" value="https://www.youtube.com/embed/cy2K--Owzy0"></param>
                <embed src="https://www.youtube.com/embed/cy2K--Owzy0"></embed>
            </object>
            <h5>Click Below to Purchase Inventory with Credit Card</h5>
            <p><a href="https://checkout.square.site/merchant/MLZMCABT6VC84/checkout/53ERSRX7XUBPWNWGNIGNDVEM" target="_blank" class="link-primary btn btn-secondary credit">Purchase Inventory</a></p>
        </div>
        <div class="card-body">        
            <h5>To Purchase Inventory Via Wire, here is the Banking info</h5>
            <br>
            <div>
                <h5>Smart Wholesale Banking</h5>
                <h5>Account Name: Smart Wholesale LLC <br>Bank Name: Bank of America <br>Account #: 4850 1209 4425 <br>Routing #: 026009593 </h5>
                <h5>Bank Address <br> 200 E Powell Blvd Gresham, OR 97030 <br> 503-665-3159</h5>
                <br>
                <h5><i>*Note - Please put your name and the name of your company in the memo of the wire</h5>
            </div>
        </div>

    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(".credit").click(function() {   
        $.get('/credit-click', function(data) {

        });
    });
</script>
<?= $this->endSection() ?>