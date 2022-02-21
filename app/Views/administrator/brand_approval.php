<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
  <div class="card">

    <form id="brand-list">
      <div class="card-header">
        <div class="row">
          <div class="col-md-4">

            <div class="form-group row">
              <label for="" class="col-sm-3 col-form-label font-weight-bold">CLIENT:</label>
              <div class="col-sm-9">
                <select class="form-control select-search user-select user_list" name="user" disabled data-fouc>
                  <option value="0">...</option>
                  <?php foreach ($users->getResultArray() as $client) : ?>
                    <option value="<?= $client['id'] ?>"><?= $client['fullname'] . " (" . $client['company'] . ")" ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>
          <div class="col-md-4">

          </div>
          <div class="col-md-4">

            <div class="custom-control custom-switch mb-2 text-right">

              <input type="checkbox" class="custom-control-input checkbox_check" id="sc_ls_c">
              <label class="custom-control-label font-weight-bold" for="sc_ls_c">Edit ON/OFF</label>
            </div>

          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group" id="brandcontent">
          <p class="font-weight-semibold"></p>
          <div class="row brandlist">
            <?php foreach ($brands->getResultArray() as $brand) : ?>
              <div class="col-md-2">
                <label class="custom-control custom-control-dark custom-checkbox mb-2">
                  <input type="checkbox" class="custom-control-input brand_check">
                  <span class="custom-control-label font-weight-bold"><?= $brand['brand_name'] ?></span>
                </label>
              </div>
            <?php endforeach ?>
          </div>
        </div>
        <div class="text-center">
          <button class="btn btn-secondary btn_check" disabled><i class="icon-checkmark2"></i> Save</button>
          <button type="button" class="btn btn-danger btn_add" data-toggle="modal" data-target="#modal_form_upload" disabled><i class="icon-plus2 mr-2"></i>Add Brand</button>
        </div>
      </div>

    </form>

  </div>
  <div id="modal_form_upload" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title">Add New Brand</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form id="formaddbrand">
          <?php csrf_field() ?>
          <div class="modal-body">
            <div class="form-group text-left">
              <label class="text-left">Brand Name:</label>
              <label class="custom-file">
                <input type="text" name="brand" class="form-control" required>
              </label>
            </div>
          </div>

          <div class="modal-footer">
            <div class="text-right">
              <button type="submit" class="btn btn-secondary add-brand">Save <i class="icon-paperplane ml-2"></i></button>
            </div>

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
<script src="/assets/js/demo_pages/form_select2.js"></script>
<script src="/assets//js/plugins/extensions/jquery_ui/interactions.min.js"></script>
<script src="/assets//js/plugins/forms/selects/select2.min.js"></script>
<script src="/assets/js/plugins/notifications/jgrowl.min.js"></script>
<script src="/assets/js/plugins/notifications/noty.min.js"></script>
<script src="/assets/js/demo_pages/extra_jgrowl_noty.js"></script>
<script>
  $(document).ready(function() {
    $('.checkbox_check').change(function() {
      if ($('input.checkbox_check').is(':checked')) {
        $('.brand_check').prop("disabled", false);
        $('.btn_check').prop("disabled", false);
        $('.btn_add').prop("disabled", false);
        $('.user_list').prop("disabled", false);
      } else {
        $('.brand_check').prop("disabled", true);
        $('.btn_check').prop("disabled", true);
        $('.btn_add').prop("disabled", true);
        $('.user_list').prop("disabled", true);
      }
    });

    $('.user-select').change(function() {
      const id = $(this).val();
      $.get('/get-brands-client', {
        userid: id
      }, function(data) {
        $('.brandlist').html("");
        const brand = JSON.parse(data);
        for (var i = 0; i < brand.length; i++) {
          if (brand[i]['checked'] == 1) {
            $('.brandlist').append('<div class="col-md-2"> <label class="custom-control custom-control-dark custom-checkbox mb-2"> <input type="checkbox" name="brand[]" value="' + brand[i]['id'] + '" class="custom-control-input brand_check" checked> <span class="custom-control-label font-weight-bold">' + brand[i]['brand_name'] + '</span> </label> </div>');
          } else {
            $('.brandlist').append(' <div class="col-md-2"> <label class="custom-control custom-control-dark custom-checkbox mb-2"> <input type="checkbox" name="brand[]" value="' + brand[i]['id'] + '" class="custom-control-input brand_check" > <span class="custom-control-label font-weight-bold">' + brand[i]['brand_name'] + '</span> </label> </div>');
          }
        }
      })
    });
  });

  $("form#brand-list").on("submit", function(e) {
    e.preventDefault();
    $.post('<?= base_url('/save-brand-client') ?>', $(this).serialize(), function(data) {
      if (data != "0") {
        new Noty({
          text: 'Brands has been saved.',
          type: 'alert'
        }).show();
      } else {
        new Noty({
          text: 'Please select client first',
          type: 'alert'
        }).show();
      }
    });
  });


  $("form#formaddbrand").on("submit", function(e) {
    e.preventDefault();
    $.post('<?= base_url('/add-brand') ?>', $(this).serialize(), function(data) {
      new Noty({
        text: 'New Brand has been saved.',
        type: 'alert'
      }).show();
      $("#brandcontent").load(location.href + " #brandcontent");

      $('#modal_form_upload').modal('hide');
    });
  });
</script>


<?= $this->endSection() ?>