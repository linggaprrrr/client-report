<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>
<script src="assets/js/demo_pages/components_collapsible.js"></script>
<div class="content">
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item">
          <a href="#icon-only-tab1" class="nav-link active" data-toggle="tab">
            <span class="d-lg-none ml-2">Active</span>
            Brands
          </a>
        </li>

        <li class="nav-item">
          <a href="#icon-only-tab2" class="nav-link" id="myfav" data-toggle="tab">
            <span class="d-lg-none ml-2">Inactive</span>
            Clients
          </a>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="icon-only-tab1">
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
                <button type="button" class="btn btn-success btn_import" data-toggle="modal" data-target="#modal_form_upload_file" disabled><i class="icon-file-upload mr-2"></i>Import Brand</button>
              </div>
            </div>

          </form>
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
          <div id="modal_form_upload_file" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                  <h5 class="modal-title">Upload Brand</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="<?= base_url('upload-brand') ?>" method="POST" enctype="multipart/form-data">
                  <?php csrf_field() ?>
                  <div class="modal-body">
                    <div class="form-group">
                      <label>File:</label>
                      <label class="custom-file">
                        <input type="file" name="file" class="custom-file-input" id="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                        <span class="custom-file-label" id="file-upload-filename">Choose file</span>
                      </label>
                      <span class="form-text text-muted">Accepted formats: xls/xlsx. Max file size 10Mb</span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <div class="text-right">
                      <button type="submit" class="btn btn-secondary">Save <i class="icon-paperplane ml-2"></i></button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="icon-only-tab2">
          <div class="row">
            <div class="col-md-4">
              <button type="button" class="btn btn-danger btn_assign_brand" data-toggle="modal" data-target="#modal_form_assign_brand"><i class="icon-file-upload mr-2"></i>Assign Brand</button>
              <div id="modal_form_assign_brand" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header bg-secondary text-white">
                      <h5 class="modal-title">Upload Brands Approved Per Store</h5>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="<?= base_url('upload-brand-per-store') ?>" method="POST" enctype="multipart/form-data">
                      <?php csrf_field() ?>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>File:</label>
                          <label class="custom-file">
                            <input type="file" name="store" class="custom-file-input" id="file-upload2" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                            <span class="custom-file-label" id="file-upload-filename2">Choose file</span>
                          </label>
                          <span class="form-text text-muted">Accepted formats: xls/xlsx. Max file size 10Mb</span>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <div class="text-right">
                          <button type="submit" class="btn btn-secondary">Save <i class="icon-paperplane ml-2"></i></button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form id="brand-list">
            <div class="card-header">
              <div class="row">
                <div class="col-md-4">

                  <div class="form-group row">
                    <label for="" class="col-sm-3 col-form-label font-weight-bold">Brand:</label>
                    <div class="col-sm-9">
                      <select class="form-control select-search brand-select brand_list" name="brands" data-fouc>
                        <option value="0">...</option>
                        <?php foreach ($brands->getResultArray() as $brand) : ?>
                          <option value="<?= $brand['id'] ?>"><?= $brand['brand_name'] ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4">

                  <div class="custom-control custom-switch mb-2 text-right">
                    <h6 class="font-weight-bold">Total: <span id="totalclient">0</span></h6>
                  </div>

                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group" id="clientcontent">
                <p class="font-weight-semibold"></p>
                <div class="row userlist">
                  <?php foreach ($users->getResultArray() as $user) : ?>
                    <div class="col-md-3">
                      <dl class="mb-0">
                        <dt class="font-weight-bold"><i class="icon-checkmark-circle"></i> <?= $user['fullname'] ?> (<?= $user['company'] ?>)</dt>
                      </dl>
                    </div>
                  <?php endforeach ?>
                </div>
              </div>

            </div>

          </form>
        </div>
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
    var input = document.getElementById('file-upload');
    var infoArea = document.getElementById('file-upload-filename');
    
    var input2 = document.getElementById('file-upload2');
    var infoArea2 = document.getElementById('file-upload-filename2');

    input.addEventListener('change', showFileName);
    input2.addEventListener('change', showFileName2);

    function showFileName(event) {
      var input = event.srcElement;
      var fileName = input.files[0].name;
      infoArea.textContent = '' + fileName;
    }

    function showFileName2(event) {
      var input2 = event.srcElement;
      var fileName2 = input2.files[0].name;
      infoArea2.textContent = '' + fileName2;
    }

    $('.checkbox_check').change(function() {
      if ($('input.checkbox_check').is(':checked')) {
        $('.brand_check').prop("disabled", false);
        $('.btn_check').prop("disabled", false);
        $('.btn_add').prop("disabled", false);
        $('.user_list').prop("disabled", false);
        $('.btn_import').prop("disabled", false);
      } else {
        $('.brand_check').prop("disabled", true);
        $('.btn_check').prop("disabled", true);
        $('.btn_add').prop("disabled", true);
        $('.user_list').prop("disabled", true);
        $('.btn_import').prop("disabled", true);
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

    $('.brand-select').change(function() {
      const id = $(this).val();
      $.get('/get-client-by-brand', {
        brandid: id
      }, function(data) {
        $('.userlist').html("");
        const user = JSON.parse(data);
        $('#totalclient').html(user.length);
        for (var i = 0; i < user.length; i++) {
          if (user[i]['checked'] == 1) {
            $('.userlist').append('<div class="col-md-3"><dl class="mb-0"> <dt class="font-weight-bold"><i class="icon-checkmark-circle"></i> ' + user[i]['fullname'] + ' (' + user[i]['company'] + ')</dt> </dl></div>');
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