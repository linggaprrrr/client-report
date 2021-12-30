<?= $this->extend('administrator/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">

        <div class="card-body">
            <div class="card-body d-lg-flex align-items-lg-center justify-content-lg-between flex-lg-wrap">

                <div class="d-flex align-items-center mb-3 mb-lg-0">
                    <a href="#" class="btn bg-transparent border-indigo text-indigo rounded-pill border-2 btn-icon">
                        <i class="icon-newspaper"></i>
                    </a>
                    <div class="ml-3">
                        <h5 class="font-weight-semibold mb-0"><?= $allNews->getNumRows() ?></h5>
                        <span class="text-muted">Total News</span>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-teal" data-toggle="modal" data-target="#modal_news"><i class="icon-add-to-list mr-2"></i>Create News</button>
                    <div id="modal_news" class="modal fade" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-secondary text-white">
                                    <h5 class="modal-title"><i class="icon-ticket mr-2"></i>Create News</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="<?= base_url('create-news') ?>" method="POST" enctype="multipart/form-data">
                                    <?php csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= session()->get('user_id') ?>">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Tittle:</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend">
                                                    <span class="input-group-text"><i class="icon-bubble-lines3"></i></span>
                                                </span>
                                                <input type="text" class="form-control" name="title" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Message:</label>
                                            <div class="input-group">
                                                <textarea id="summernote" name="message" required></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-secondary">Send <i class="icon-paperplane ml-2"></i></button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table datatable-basic-2" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>News </th>
                        <th style="width: 15%" class="text-center">Date </th>
                        <th class="text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($allNews->getNumRows() > 0) : ?>
                        <?php $no = 1 ?>
                        <?php foreach ($allNews->getResultArray() as $news) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>
                                    <li class="media" style="align-content: center;">
                                        <div class="mr-3">
                                            <i class="icon-newspaper  mr-3 icon-3x"></i>
                                        </div>

                                        <div class="media-body">
                                            <span class="media-title d-block font-weight-semibold"><?= strtoupper($news['title']) ?></span>
                                            <?= $news['message'] ?>
                                        </div>
                                    </li>
                                </td>
                                <td class="text-center"><?= strtoupper(date("M-d-y H:i", strtotime($news['date']))) ?></td>

                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu7"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#" data-toggle="modal" data-target="#modal_news-edit" data-id="<?= $news['id'] ?>" class="dropdown-item edit_news"><i class="icon-pencil text-warning"></i> Edit News</a>
                                                <form action="<?= base_url("/delete-news/" . $news['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="dropdown-item"><i class="icon-cross2 text-danger"></i> Delete</a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>
            <div id="modal_edit_news" class="modal fade" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title"><i class="icon-ticket mr-2"></i>Edit News</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form action="<?= base_url('update-news') ?>" method="POST" enctype="multipart/form-data">
                            <?php csrf_field() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Tittle:</label>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text"><i class="icon-bubble-lines3"></i></span>
                                        </span>
                                        <input type="hidden" name="id" id="id" required>
                                        <input type="text" class="form-control" name="title" id="title" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Message:</label>
                                    <div class="input-group">
                                        <textarea id="summernote_edit" name="message" required></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-secondary">Send <i class="icon-paperplane ml-2"></i></button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url() ?>/assets/js/plugins/tables/datatables/datatables.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>


<script>
    $(document).ready(function() {
        $('#summernote').summernote();
        $('#summernote_edit').summernote();

        $('.edit_news').on("click", function() {
            $('#modal_edit_news').modal('show');
            var id = $(this).attr('data-id');
            $.get("<?= base_url("/news") ?>/" + id, function(data) {
                var news = JSON.parse(data);
                $('#id').val(news['id']);
                $('#title').val(news['title']);
                $("#summernote_edit").summernote("code", news['message']);
            });
        });


    });

    var DatatableBasic = function() {
        var _componentDatatableBasic = function() {
            if (!$().DataTable) {
                console.warn('Warning - datatables.min.js is not loaded.');
                return;
            }

            // Setting datatable defaults
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    targets: [3]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                    }
                }
            });

            // Apply custom style to select
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sLengthSelect": "custom-select"
            });

            // Basic datatable
            $('.datatable-basic-2').DataTable({
                "bInfo": false,
                "bLengthChange": false,
                "bAutoWidth": false
            });

            // Alternative pagination
            $('.datatable-pagination').DataTable({
                pagingType: "simple",
                language: {
                    paginate: {
                        'next': $('html').attr('dir') == 'rtl' ? 'Next &larr;' : 'Next &rarr;',
                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Prev' : '&larr; Prev'
                    }
                }
            });

            // Datatable with saving state
            $('.datatable-save-state').DataTable({
                stateSave: true
            });

            // Scrollable datatable
            var table = $('.datatable-scroll-y').DataTable({
                autoWidth: true,
                scrollY: 300
            });

            // Resize scrollable table when sidebar width changes
            $('.sidebar-control').on('click', function() {
                table.columns.adjust().draw();
            });
        };


        //
        // Return objects assigned to module
        //

        return {
            init: function() {
                _componentDatatableBasic();
            }
        }
    }();


    // Initialize module
    // ------------------------------

    document.addEventListener('DOMContentLoaded', function() {
        DatatableBasic.init();
    });
</script>
<?= $this->endSection() ?>