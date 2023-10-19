 <?= $this->extend('client/layout/template') ?>

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
             <object style="width: 683px;  height: 360px" >
                <param name="movie" value="https://www.youtube.com/embed/cy2K--Owzy0" width="683" height="360"></param>
                <embed src="https://www.youtube.com/embed/cy2K--Owzy0" width="683" height="360"></embed>
            </object>
            
            <h1>Click Below to Purchase Inventory with Credit Card</h1>
            <p><a href="https://checkout.square.site/merchant/MLZMCABT6VC84/checkout/53ERSRX7XUBPWNWGNIGNDVEM" target="_blank" class="link-primary btn btn-secondary credit">Purchase Inventory</a></p>
        </div>
        <div class="card-body">
            <h2>To Purchase Inventory Via Wire, here is the Banking info</h2>
            <br>
            <div>
                <h2>Smart Wholesale Banking</h2>
                <h2>Account Name: Smart Wholesale LLC <br>Bank Name: Bank of America <br>Account #: 4850 1209 4425 <br>Routing #: 026009593 </h2>
                <h2>Bank Address <br> 200 E Powell Blvd Gresham, OR 97030 <br> 503-665-3159</h2>
                <br>
                <h4><i>*Note - Please put your name and the name of your company in the memo of the wire</h4>
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