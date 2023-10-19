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
        </div>
        <div class="card-body">
            <iframe width="683" height="360"src="https://www.youtube.com/embed/WZYZBXamC5s" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            <h5>Learn how the payment process works and how to access your payment reports.</h5>
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