@extends('vMaster')
@section('title','Calculate POQ (Periodic Order Quantity)')
@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Calculate POQ</h4>
            <h6>Calculate POQ (Periodic Order Quantity)</h6>
        </div>
    </div>
    <ul class="table-top-head">
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
        </li>
    </ul>
</div>

<div class="card">
    <form id="form_submit">
        @csrf
        <div class="card-body">
            <div class="col-lg-12 col-sm-12">
                <div class="mb-3">
                    <label class="form-label">Product<span class="text-danger ms-1">*</span></label>
                    <select name="product_id" id="product_id" class="form-control">
                        <option value="">-- Select Product --</option>
                        @foreach ($product as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                {{-- <a href="javascript:void(0);" class="btn btn-secondary me-2 shadow-none">Cancel</a> --}}
                <button type="submit" class="btn btn-primary shadow-none">Calculate</button>
            </div>
        </div>
    </form>
</div>

<div class="page-header">
    <ul class="table-top-head">
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" class="import-excel" title="Excel"><img src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
        </li>
    </ul>
</div>

<!-- / list -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
        <div class="search-set">
            <div class="search-input">
                <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table datatable" id="table">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Average Demand</th>
                        <th>Demand Year</th>
                        <th>Price</th>
                        <th>EOQ</th>
                        <th>POQ</th>
                        <th>Calculation Date</th>
                        <th class="no-sort"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- / list -->

<!-- import modal -->
<div class="modal fade" id="import-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Export POQ to Excel</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="exportForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Start date<span class="text-danger ms-1">*</span></label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End date<span class="text-danger ms-1">*</span></label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('js')
<script>
    var url_calculate = "{{ route('poq.calcuate',':id') }}";
    var url           = "{{ route('poq.data') }}";
    var url_delete    = "{{ route('poq.delete',':id') }}";
    var url_export    = "{{ route('poq.export') }}";
</script>
<script>
    $(document).ready(function() {
        var table = $("#table").dataTable({
            ...defaultDatatableSettings,
            processing: true,
            serverSide: true,
            ajax: url,
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'product_id',
                    name: 'product_id',
                },
                {
                    data: 'unit',
                    name: 'unit',
                },
                {
                    data: 'average_demand',
                    name: 'average_demand',
                },
                {
                    data: 'demand_per_year',
                    name: 'demand_per_year',
                },
                {
                    data: 'unit_price',
                    name: 'unit_price',
                },
                {
                    data: 'eoq',
                    name: 'eoq',
                },
                {
                    data: 'poq',
                    name: 'poq',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row, meta) {

                        // return `
                        //     <div class="edit-delete-action">
                        //         <a class="me-2 edit-icon  p-2 show_data" href="javascript:void(0)" data-id="${row.id}">
                        //             <i data-feather="eye" class="feather-eye"></i>
                        //         </a>
                        //         <a class="p-2 delete" data-id="${row.id}" href="javascript:void(0);">
                        //             <i data-feather="trash-2" class="feather-trash-2"></i>
                        //         </a>
                        //     </div>
                        // `;
                        return `
                            <div class="edit-delete-action">
                                <a class="p-2 delete" data-id="${row.id}" href="javascript:void(0);">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>
                        `;
                    }
                },
            ],
            columnDefs: [
                {
                    targets: 8,
                    className: 'action-table-data',
                }
            ]
        });

        $("#form_submit").submit(function(e) {
            e.preventDefault();
            var id = $("#product_id").val();
            if (id === "") {
                showAlert('Product has not been selected yet','Opps ...','info');
                return;
            }

            var url = url_calculate.replace(':id',id);


            $.ajax({
                url : url,
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    showLoadingAlert();
                },success: function(response) {
                    var message = response.message;
                    var title = response.title;
                    showAlert(message,title,'success');
                    table.DataTable().ajax.reload();
                    console.log(response);

                },error : function(xhr) {
                    var message = xhr.responseJSON.message;
                    var title = xhr.responseJSON.title;
                    showAlert(message,title,'error');
                },
            });
        });

        $(document).on('click','.delete', function() {
            var id = $(this).data('id');
            var url = url_delete.replace(":id",id);
            Swal.fire({
                title: "Delete POQ?",
                text: "You won't be able to revert this!",
                icon: "warning",
                width: '300px', // default-nya 500px
                customClass: {
                  popup: 'custom-swal-size'
                },

                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                reverseButtons: true
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : url,
                        type: "DELETE",
                        beforeSend: function() {
                            showLoadingAlert();
                        },success: function(response) {
                            var message = response.message;
                            var title  = response.title;
                            showAlert(message,title,'success');
                        },error : function(xhr) {
                            var messege  = xhr.responseJSON.message;
                            var title    = xhr.responseJSON.title;
                            showAlert(messege,title,'error');
                        },complete: function() {
                            table.DataTable().ajax.reload();
                        }
                    });
                }
              });
        });

        $(document).on('click','.import-excel',function(e) {
            $("#import-modal").modal('show');
        });

        $('#exportForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let formData = form.serialize();

            $.ajax({
                url: url_export,
                method: 'POST',
                data: formData,
                xhrFields: {
                    responseType: 'blob' // Penting agar bisa download file biner
                },
                beforeSend: function() {
                    showLoadingAlert();
                },
                success: function(blob, status, xhr) {
                    // Ambil nama file dari header Content-Disposition
                    const disposition = xhr.getResponseHeader('Content-Disposition');
                    let filename = "report.xlsx";

                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        const matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                        if (matches != null && matches[1]) {
                            filename = matches[1].replace(/['"]/g, '');
                        }
                    }

                    // Buat link unduhan dan klik otomatis
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);

                    showAlert('File downloaded successfully', 'Success', 'success');
                    $("#import-modal").modal('hide');
                },
                error: function(xhr) {
                    const res = xhr.responseJSON;
                    showAlert(res?.message || 'An error occurred during export.', 'Error', 'error');
                }
            });
        });


    });
</script>
@endpush
