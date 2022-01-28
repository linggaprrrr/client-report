<?= $this->extend('va/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">

    <!-- Blocks with chart -->


    <!-- /blocks with chart -->

</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/ui/moment/moment.min.js"></script>
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>

<?= $this->endSection() ?>