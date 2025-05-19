@extends('vMaster')
@section('title','Product')
@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4 class="fw-bold">Product List</h4>
            <h6>Manage your products</h6>
        </div>
    </div>
    <ul class="table-top-head">
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
        </li>
    </ul>
    <div class="page-btn">
        <a href="#" class="btn btn-primary add_button" id="add_button"><i class="ti ti-circle-plus me-1"></i>Add Product</a>
    </div>
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
                        <th>Product Code </th>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Category</th>
                        <th>Stock</th>
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
                    <h4>Add Product</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_add"  action="{{ route('product.save') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">SKU<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" name="product_code" required placeholder="SKU or Unique Code Product">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" name="product_name" required placeholder="Product Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            @foreach ($category as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" name="unit" required placeholder="Tablet, Capsul, ext">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="stock" required placeholder="Initial stock">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Min Stock<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="min_stock" required placeholder="Minimum stok sebelum reorder">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selling Price<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="selling_price" required placeholder="selling price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lead Time<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="lead_time" required placeholder="Order lead time (days)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Average Demand /month<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="average_demand" required placeholder="Average Demand /month">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordering Cost</label>
                        <input type="number" class="form-control" name="ordering_cost"  placeholder="Ordering Cost">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Holding Cost Percent<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="holding_cost_percent"  placeholder="Holding Cost Percent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
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
                    <h4>Edit Product</h4>
                </div>
                <button type="button" class="close bg-danger text-white fs-16" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_edit" >
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">SKU<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" id="product_code" name="product_code" required placeholder="SKU or Unique Code Product">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Product Name<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required placeholder="Product Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                        <select name="category_id" id="category_id" class="form-control" required>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit<span class="text-danger ms-1">*</span></label>
                        <input type="text" class="form-control" id="unit" name="unit" required placeholder="Tablet, Capsul, ext">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stock<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" id="stock" name="stock" required placeholder="Initial stock">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Min Stock<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" id="min_stock" name="min_stock" required placeholder="Minimum stok sebelum reorder">
                    </div>
                     <div class="mb-3">
                        <label class="form-label">Selling Price<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" name="selling_price" id="selling_price" required placeholder="selling price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lead Time<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" id="lead_time" name="lead_time" required placeholder="Order lead time (days)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Average Demand /month<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" id="average_demand" name="average_demand" required placeholder="Average Demand /month">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ordering Cost</label>
                        <input type="number" class="form-control" name="ordering_cost" id="ordering_cost"  placeholder="Ordering Cost">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Holding Cost Percent<span class="text-danger ms-1">*</span></label>
                        <input type="number" class="form-control" id="holding_cost_percent" name="holding_cost_percent"  placeholder="Holding Cost Percent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('js')
<script>
    var url         = "{{ route('product.data') }}";
    var url_show    = "{{ route('product.show',':id') }}";
    var url_edit    = "{{ route('product.edit',':id') }}";
    var url_delete  = "{{ route('product.delete',':id') }}";
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
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_code',
                        name: 'product_code',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                    },
                    {
                        data: 'category',
                        name: 'category',
                    },
                    {
                        data: 'stock',
                        name: 'stock',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {

                            return `
                                <div class="edit-delete-action">
                                    <a class="me-2 edit-icon  p-2 show_data" href="javascript:void(0)" data-id="${row.id}">
                                        <i data-feather="eye" class="feather-eye"></i>
                                    </a>
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
                        targets: 6,
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
                        var selected = data.category_id;
                        $("#form_edit").attr('action',url_update);
                        $("#product_name").val(data.name);
                        $("#product_code").val(data.product_code);
                        $("#unit").val(data.unit);
                        $("#stock").val(data.stock);
                        $("#min_stock").val(data.min_stock);
                        $("#average_demand").val(data.average_demand);
                        $("#ordering_cost").val(data.ordering_cost);
                        $("#holding_cost_percent").val(data.holding_cost_percent);
                        $("#lead_time").val(data.lead_time);
                        $("#selling_price").val(data.selling_price);

                        // Kosongkan opsi lama di select category
                        $('#category_id').empty();

                        // Tambahkan opsi default "-- Select Category --"
                        $('#category_id').append('<option value="">-- Select Category --</option>');

                        // Loop untuk menambahkan kategori berdasarkan data yang ada
                        $.each(response.category, function(key, value) {
                            let isSelected = (value.id === selected) ? 'selected' : '';
                            $('#category_id').append(
                                `<option value="${value.id}" ${isSelected}>${value.name}</option>`
                            );
                        });
                        $("#edit-modal").modal('show');
                    },error : function(xhr) {
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
                        $("#edit-modal").modal('hide');
                        $("#form_edit").trigger('reset');
                    },error : function(xhr) {
                        var message = xhr.responseJSON.message;
                        var titlle  = xhr.responseJSON.title;
                        showAlert(message,titlle,'error');
                    },complete: function() {
                        table.DataTable().ajax.reload();

                    }
                })
            });

            $(document).on('click','.delete', function() {
                var id = $(this).data('id');
                var url = url_delete.replace(":id",id);
                Swal.fire({
                    title: "Delete Product?",
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

        });
    </script>
@endpush
