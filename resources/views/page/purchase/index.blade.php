@extends('vMaster')
@section('title','Purchase Order')
@section('content')
@php
    $role = auth()->user()->role;
@endphp
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Purhase Order List</h4>
            <h6>Manage your Purchase Order</h6>
        </div>
    </div>
    <ul class="table-top-head">
         <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" class="import-excel" title="Excel"><img src="assets/img/icons/excel.svg" alt="img"></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
        </li>
    </ul>
    @if (auth()->user()->role != 'supplier')
        <div class="page-btn">
            <a href="#" class="btn btn-primary add_button" id="add_button"><i class="ti ti-circle-plus me-1"></i>Create Purchase Order</a>
        </div>

    @endif
</div>

<!-- /product list -->
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
                        <th class="no-sort">
                            <label class="checkboxs">
                                <input type="checkbox" id="select-all">
                                <span class="checkmarks"></span>
                            </label>
                        </th>
                        <th>PO Number </th>
                        <th>Supplier</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="no-sort"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /product list -->



<!-- add modal -->
<div class="modal fade" id="add-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Create Purchase Order</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_add"  action="{{ route('purchase.order.save') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Suppliers<span class="text-danger ms-1">*</span></label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">-- Select Suppliers --</option>
                            @foreach ($suppliers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order Date<span class="text-danger ms-1">*</span></label>
                        <input type="date" class="form-control" name="order_date" required >
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Products</label>
                        <table class="table table-bordered" id="product-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th><button type="button" class="btn btn-sm btn-success" id="add-product">+</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="products[0][product_id]" class="form-control" required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="products[0][quantity]" class="form-control" required></td>
                                    <td><input type="number" name="products[0][price]" class="form-control" required step="0.01"></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-product">-</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note<span class="text-danger ms-1"></span></label>
                        <input type="text" class="form-control" name="note" >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create purchase order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    @if (auth()->user()->role == 'supplier')
                    <h4>Detail Purchase Order</h4>
                    @else
                    <h4>Edit Purchase Order</h4>
                    @endif
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit" >
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Suppliers<span class="text-danger ms-1">*</span></label>
                        <select name="supplier_id" id="supplier_id" class="form-control" required {{ $role == 'supplier' ? 'disabled' : '' }}>
                            @foreach ($suppliers as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Order Date<span class="text-danger ms-1">*</span></label>
                        <input type="date" class="form-control" id="order_date" name="order_date" required {{ $role == 'supplier' ? 'disabled' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Products</label>
                        <table class="table table-bordered" id="edit-product-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>
                                        @if (auth()->user()->role != 'supplier')
                                        <button type="button" class="btn btn-sm btn-success" id="edit-add-product">+</button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Note</label>

                        <input type="text" class="form-control" name="note" id="note">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    @if (auth()->user()->role != 'supplier')
                    <button type="submit" class="btn btn-primary">Update Purchase Order</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- import modal -->
<div class="modal fade" id="import-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Export Report Purchase Order</h4>
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
    var url         = "{{ route('purchase.order.data') }}";
    var url_show    = "{{ route('purchase.order.show',':id') }}";
    var url_edit    = "{{ route('purchase.order.update',':id') }}";
    var url_delete  = "{{ route('purchase.order.delete',':id') }}";
    var role        = "{{ auth()->user()->role }}";
    var url_export  = "{{ route('purchase.order.export') }}";

</script>
    <script>
        $(document).ready(function() {
            let editRowIndex = 0; // Global untuk pelacakan index
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
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'po_number',
                        name: 'po_number',
                    },
                    {
                        data: 'supplier_id',
                        name: 'supplier_id',
                    },
                    {
                        data: 'order_date',
                        name: 'order_date',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {

                            let actions = `
                                <div class="edit-delete-action">
                                    <a class="me-2 edit-icon p-2 show_data" href="javascript:void(0)" data-id="${row.id}">
                                        <i data-feather="eye" class="feather-eye"></i>
                                    </a>
                            `;

                            // Tampilkan tombol delete hanya jika rolenya bukan 'suppliers'
                            if (role !== 'supplier') {
                                actions += `
                                    <a class="p-2 delete" data-id="${row.id}" href="javascript:void(0);">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                `;
                            }

                            actions += `</div>`;
                            return actions;
                        }
                    },
                ],
                columnDefs: [
                    {
                        targets: 5,
                        className: 'action-table-data',
                    }
                ]
            });

            $(document).on('click','.add_button',function() {
                $("#add-modal").modal('show');
            });

            $("#form_add").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url : $(this).attr('action'),
                    data : $(this).serialize(),
                    type: "POST",
                    beforeSend:  function() {
                        showLoadingAlert();
                    },success : function(response) {
                        var message = response.message;
                        var title = response.title;
                        showAlert(message,title,'success');
                    },error : function(xhr) {
                        var message = xhr.responseJSON.message;
                        var title   = xhr.responseJSON.title;
                        showAlert(message,title,'error');
                    },complete: function() {
                        $("#add-modal").modal('hide');
                        table.DataTable().ajax.reload();
                    }
                })
            });

            $(document).on('click','.show_data',function() {
                var id          = $(this).data('id');
                var url         = url_show.replace(":id",id);
                var url_update  = url_edit.replace(":id",id);
                $.ajax({
                    url : url,
                    type: "GET",
                    beforeSend: function() {
                        showLoadingAlert();
                    },success: function(response) {
                        var data = response.data;
                        swal.close();
                        var selected = data.supplier_id;

                        $("#form_edit").attr('action', url_update);
                        $("#order_date").val(data.order_date);

                        // Reset & set supplier
                        $('#supplier_id').empty();
                        $('#supplier_id').append('<option value="">-- Select supplier --</option>');
                        $.each(response.supplier, function(key, value) {
                            let isSelected = (value.id === selected) ? 'selected' : '';
                            $('#supplier_id').append(
                                `<option value="${value.id}" ${isSelected}>${value.name}</option>`
                            );
                        });

                        // Simpan produk untuk opsi pilihan
                        window.availableProducts = response.products;
                        const tbody = $('#edit-product-table tbody');
                        tbody.empty();
                        editRowIndex = 0;

                        $.each(response.items, function (i, item) {
                            let options = '<option value="">-- Select Product --</option>';
                            $.each(response.products, function (j, p) {
                                let selected = item.product_id == p.id ? 'selected' : '';
                                options += `<option value="${p.id}" ${selected}>${p.name}</option>`;
                            });

                            let row = `
                                <tr>
                                    <td>
                                        <select name="products[${editRowIndex}][product_id]" class="form-control" required>
                                            ${options}
                                        </select>
                                    </td>
                                    <td><input type="number" name="products[${editRowIndex}][quantity]" class="form-control" value="${item.quantity}" required></td>
                                    <td><input type="number" name="products[${editRowIndex}][price]" class="form-control" value="${item.price}" required></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove-product">-</button></td>
                                </tr>
                            `;
                            tbody.append(row);
                            editRowIndex++;
                        });
                        $("#edit-modal").modal('show');
                    }
                    ,error : function(xhr) {
                        var message = xhr.responseJSON.message;
                        var title   = xhr.responseJSON.title;
                        showAlert(message,title,'error');
                    }
                })
            });

            $("#form_edit").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url : $(this).attr('action'),
                    data: $(this).serialize(),
                    type :"POST",
                    beforeSend: function() {
                        showLoadingAlert();
                    },success: function(response) {
                        var message = response.message;
                        var title   = response.title;
                        showAlert(message,title,'success');
                    },error : function(xhr) {
                        var message = xhr.responseJSON.message;
                        var titlle  = xhr.responseJSON.title;
                        showAlert(message,titlle,'error');
                    },complete: function() {
                        table.DataTable().ajax.reload();
                        $("#edit-modal").modal('hide');
                        $("#form_edit").trigger('reset');
                    }
                })
            });

            $(document).on('click','.delete', function() {
                var id = $(this).data('id');
                var url = url_delete.replace(":id",id);
                Swal.fire({
                    title: "Delete Purchase Order?",
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

            let productIndex = 1;

            $('#add-product').on('click', function () {
                let newRow = `
                <tr>
                    <td>
                        <select name="products[${productIndex}][product_id]" class="form-control" required>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" required></td>
                    <td><input type="number" name="products[${productIndex}][price]" class="form-control" required step="0.01"></td>
                    <td><button type="button" class="btn btn-sm btn-danger remove-product">-</button></td>
                </tr>`;
                $('#product-table tbody').append(newRow);
                productIndex++;
            });

            $(document).on('click', '#edit-add-product', function () {
                let products = window.availableProducts || [];
                let options = '<option value="">-- Select Product --</option>';
                $.each(products, function (j, p) {
                    options += `<option value="${p.id}">${p.name}</option>`;
                });

                let row = `
                    <tr>
                        <td>
                            <select name="products[${editRowIndex}][product_id]" class="form-control" required>
                                ${options}
                            </select>
                        </td>
                        <td><input type="number" name="products[${editRowIndex}][quantity]" class="form-control" required></td>
                        <td><input type="number" name="products[${editRowIndex}][price]" class="form-control" required></td>
                        <td><button type="button" class="btn btn-sm btn-danger remove-product">-</button></td>
                    </tr>
                `;
                $('#edit-product-table tbody').append(row);
                editRowIndex++;
            });


            $(document).on('click', '.remove-product', function () {
                $(this).closest('tr').remove();
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
