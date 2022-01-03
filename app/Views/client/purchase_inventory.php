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
            <h2>Click below link to put purchase inventory</h2>
            <p><a href="https://checkout.square.site/merchant/MLZMCABT6VC84/checkout/53ERSRX7XUBPWNWGNIGNDVEM" target="_blank" class="link-primary btn btn-secondary">Purchase Inventory</a></p>

        </div>
        <div class="card-body">
            <h2>To Purchase Inventory Via Wire, here is the Banking info</h2>

            <div class="bank-account">
                <p>Smart Wholesale Banking</p>
                <p>Account Name: Smart Wholesale LLC</p>
                <p>Bank Name: Bank of America</p>
                <p>Account #: 4850 1209 4425</p>
                <p>Routing #: 026009593</p>
                <br>
                <p>Bank Address <br> 200 E Powell Blvd Gresham, OR 97030 <br> 503-665-3159</p>
                <p></p>
                <p></p>
                <p><i>*Note - Please put your name and the name of your company in the memo of the wire</i></p>
            </div>
        </div>

    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<?= $this->endSection() ?>