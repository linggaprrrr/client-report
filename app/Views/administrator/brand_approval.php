<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-4">
            <form>
              <select class="form-control" name="user"> 
                <option value="0">...</option> 
                <?php foreach($users->getResultArray() as $client): ?>    
                   <option value="<?= $client['id'] ?>"><?= $client['fullname'] ?></option> 
                <?php endforeach ?>
              </select>  
            </form>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
            <p class="font-weight-semibold"></p>
            <form id="brand_approval">
              <div class="row">                                  
                <?php foreach($brands->getResultArray() as $brand) : ?>
                    <div class="col-md-2">  
                    <label class="custom-control custom-control-dark custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input" disabled>
                        <span class="custom-control-label font-weight-bold"><?= $brand['brand_name'] ?></span>
                    </label>
                    </div>
                <?php endforeach ?>                            
              </div>
            </form>
        </div>
      </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="/assets/js/demo_pages/datatables_basic.js"></script>

<script>
    
</script>


<?= $this->endSection() ?>