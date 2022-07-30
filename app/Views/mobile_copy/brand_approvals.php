<?= $this->extend('mobile/layout/template') ?>
<script src="assets/js/demo_pages/components_popups.js"></script>
<?= $this->section('content') ?>
<style>
    .brands {
        padding-right: 30px;
        font-size: 11px;
    }

    .brands-nopad {
        font-size: 11px;
    }

    .brand-logo {
        margin-bottom: 10px;
    }
</style>
<div class="content">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item">
                    <a href="#icon-only-tab1" class="nav-link active" data-toggle="tab">
                        <i class="fa fa-amazon"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#icon-only-tab2" class="nav-link" id="myfav" data-toggle="tab" data-popup="tooltip" data-trigger="click">
                        <i class="fa fa-heart"></i>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="icon-only-tab1">
                    <span>
                        <p class="text-justify"><b>Purchasing Brand Approvals is a great idea and a strategic long term play for your Amazon FBA store. Getting "ungated" in a brand gives you a leg up on other sellers who are not approved for those brands. Less competition tends to garner higher sales volume as well as the potential for higher margins. </b></p>
                        <p class="text-justify"><b>Please see below for some of the brands we suggest. Please note that because someone is ungated in a specific brand, does not mean that any subsequent order will be only that brand. All orders will have a mix of unrestricted merchandise as well as restricted merchandise depending on availability. If you are interested in purchasing brand approvals please fill out the link below.</b></p>
                    </span>
                    <div class="text-center">
                        <div class="row" style="align-items: center; justify-content: center;">
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/michael_kors.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/lucky_brand.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/steve_madden.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/tommy_hilfiger.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/calvin_klein.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/dkny.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/guess.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/nike.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/adidas.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/under_armour.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/puma.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/tnf.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/coloumbia.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/champion.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/sperry.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/toms.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/timberland.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/convers.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/kate_spade.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/polo.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/anne_klein.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/kenneth_cole.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/fila.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/reebok.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/sam_edelmam.webp"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/fossil.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/levis.jpg"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/volcom.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/fox.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/vera.png"></div>
                            <div class="col-sm-2 brand-logo"><img src="/assets/images/brands/cole_haan.png"></div>
                        </div>  
            
                        <br>
                        <div class="table-responsive">
                            <table class="table" style="margin-left: auto;margin-right: auto;">
                                <tr>
                                    <th class="brands">MICHAEL KORS</td>
                                    <th class="brands">CHAMPION</td>
                                    <th class="brands-nopad">KENNETH COLE</td>
                                </tr>
                                <tr>
                                    <th class="brands">LUCKY BRAND</td>
                                    <th class="brands">SPERRY</td>
                                    <th class="brands-nopad">LEVI</td>
                                </tr>
                                <tr>
                                    <th class="brands">STEVE MADDEN</td>
                                    <th class="brands">UGG</td>
                                    <th class="brands-nopad">VOLCOM</td>
                                </tr>
                                <tr>
                                    <th class="brands">TOMMY HILFIGER</td>
                                    <th class="brands">TIMBERLAND</td>
                                    <th class="brands-nopad">FOX</td>
                                </tr>
                                <tr>
                                    <th class="brands">CALVIN KLEIN</td>
                                    <th class="brands">CONVERSE</td>
                                    <th class="brands-nopad">VERA BRADLEY</td>
                                </tr>
                                <tr>
                                    <th class="brands">DKNY</td>
                                    <th class="brands">KATE SPADE</td>
                                    <th class="brands-nopad">FOSSIL</td>
                                </tr>
                                <tr>
                                    <th class="brands">GUESS</td>
                                    <th class="brands">RALPH LAUREN POLO</td>
                                    <th class="brands-nopad">TOMS</td>
                                </tr>
                                <tr>
                                    <th class="brands">NIKE</td>
                                    <th class="brands">ANNE KLEIN</td>
                                    <th class="brands-nopad">COLE HAAN</td>
                                </tr>
                                <tr>
                                    <th class="brands">ADIDAS</td>
                                    <th class="brands">KENNETH COLE</td>
                                    <th class="brands-nopad">COLUMBIA</td>
                                </tr>
                                <tr>
                                    <th class="brands">UNDER ARMOUR</td>
                                    <th class="brands">FILA</td>
                                    <th class="brands-nopad">SAM EDELMAN</td>
                                </tr>
                                <tr>
                                    <th class="brands">PUMA</td>
                                    <th class="brands">REEBOK</td>
                                    <th class="brands-nopad">NORTH FACE</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="icon-only-tab2">
                    <div class="form-group">
                        <p class="font-weight-semibold">Your Brand Approval</p>
                        <form id="brand_approval">
                            <div class="border p-3 rounded">
                                <div class="row">
                                    <?php foreach ($brands as $brand) : ?>
                                        <div class="col-md-2">
                                            <label class="custom-control custom-control-dark custom-checkbox mb-2">
                                                <?php if ($brand['checked'] == 1) : ?>
                                                    <input type="checkbox" class="custom-control-input" checked disabled>
                                                <?php else : ?>
                                                    <input type="checkbox" class="custom-control-input" disabled>
                                                <?php endif ?>
                                                <span class="custom-control-label font-weight-bold"><?= $brand['brand_name'] ?></span>
                                            </label>
                                        </div>
                                    <?php endforeach ?>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /blocks with chart -->

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        $('#myfav').tooltip('show');
    });
</script>

<?= $this->endSection() ?>