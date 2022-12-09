<?= $this->extend('client/layout/template') ?>

<?= $this->section('content') ?>

<div class="content">
    <div class="card">

        <div class="card-body">
            <table class="table datatable-basic-2" style="font-size: 14px;">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>News </th>
                        <th style="width: 15%" class="text-center">Date </th>
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
                            </tr>
                        <?php endforeach ?>
                    <?php endif ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="/assets/js/plugins/tables/datatables/datatables.min.js"></script>

<script>
    /* ------------------------------------------------------------------------------
     *
     *  # Basic datatables
     *
     *  Demo JS code for datatable_basic.html page
     *
     * ---------------------------------------------------------------------------- */


    // Setup module
    // ------------------------------

    var DatatableBasic = function() {
        var _componentDatatableBasic = function() {
            if (!$().DataTable) {
                console.warn('Warning - datatables.min.js is not loaded.');
                return;
            }

            // Setting datatable defaults
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
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