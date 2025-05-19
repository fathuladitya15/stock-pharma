@extends('vMaster')
@section('title','Sales')
@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4>POS Orders</h4>
            <h6>Manage Your pos orders</h6>
        </div>
    </div>
    <ul class="table-top-head">
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" class="import-excel" title="Excel"><img src="assets/img/icons/excel.svg" alt="img"></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
        </li>
    </ul>
    <div class="page-btn">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-sales-new"><i class="ti ti-circle-plus me-1"></i>Add Sales</a>
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
        <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
            <div class="dropdown">
                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center" data-bs-toggle="dropdown">
                    Sort By : Last 7 Days
                </a>
                <ul class="dropdown-menu  dropdown-menu-end p-3">
                    <li><a href="#" class="dropdown-item sort-option" data-sort="recent">Recently Added</a></li>
                    <li><a href="#" class="dropdown-item sort-option" data-sort="asc">Ascending</a></li>
                    <li><a href="#" class="dropdown-item sort-option" data-sort="desc">Descending</a></li>
                    <li><a href="#" class="dropdown-item sort-option" data-sort="last_month">Last Month</a></li>
                    <li><a href="#" class="dropdown-item sort-option" data-sort="last_7_days">Last 7 Days</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table datatable" id="table">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Invoice Number</th>
                        <th>Grand Total</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="sales-list">

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /product list -->



<!--add popup -->
<div class="modal fade" id="add-sales-new">
    <div class="modal-dialog add-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4> Add Sales</h4>
                </div>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formSales">
                @csrf
                <div class="card border-0">
                    <div class="card-body pb-0">
                         <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Product<span class="text-danger ms-1">*</span></label>
                                    <div class="input-groupicon select-code">
                                        <input type="text" id="product-search" class="form-control" placeholder="Please type product code and select">
                                        <div class="addonset">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive no-pagination mb-3" id="product-table">
                            <table class="table datanew">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 ms-auto">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul class="border-1 rounded-2">
                                        <li class="border-bottom">
                                            <h4 class="border-end">Grand Total</h4>
                                            <h5 id="grand-total">Rp 0.00</h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Cash from Customer<span class="text-danger ms-1">*</span></label>
                                    <div class="input-groupicon select-code">
                                        <input type="text" name="amount_paid" value="" id="cash-input"  class="form-control p-2" required>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Total Purhcase<span class="text-danger ms-1">*</span></label>
                                    <div class="input-groupicon select-code">
                                        <input type="text" name="total_amount" value="0" class="form-control p-2" id="total-purchase" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label">Change<span class="text-danger ms-1">*</span></label>
                                    <div class="input-groupicon select-code">
                                        <input type="text" name="change" value="0"  id="change-output" class="form-control p-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary add-cancel me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary add-sale">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /add popup -->


<!-- details popup -->
<div class="modal fade" id="sales-details-new">
    <div class="modal-dialog sales-details-modal">
        <div class="modal-content">
            <div class="page-header p-4 border-bottom mb-0">
                <div class="add-item d-flex align-items-center">
                    <div class="page-title modal-datail">
                        <h4 class="mb-0 me-2">Sales Detail</h4>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/pdf.svg" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="assets/img/icons/printer.svg" alt="img"></a>
                    </li>
                </ul>
            </div>

            <div class="card border-0">
                <div class="card-body pb-0">
                    <div class="invoice-box table-height" style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                        <div class="row sales-details-items d-flex">
                            <div class="col-md-4 details-item">
                                <h6>Invoice Info</h6>
                                <p class="mb-0">Invoice Number: <span class="fs-16 text-primary ms-2" id="invoice_number">#SL0101</span></p>
                                <p class="mb-0">Transaction Date: <span class="ms-2 text-gray-9" id="transaction_date">Dec 24, 2024</span></p>
                            </div>
                        </div>
                        <h5 class="order-text">Order Summary</h5>
                        <div class="table-responsive no-pagination mb-3">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">

                        <div class="row">
                            <div class="col-lg-6 ms-auto">
                                <div class="total-order w-100 max-widthauto m-auto mb-4">
                                    <ul class="rounded-3">
                                        <li>
                                            <h4>Grand Total</h4>
                                            <h5 id="grand-total-detail"></h5>
                                        </li>
                                        <li>
                                            <h4>Paid</h4>
                                            <h5 id="amount-paid-detail"></h5>
                                        </li>
                                        <li>
                                            <h4>Change</h4>
                                            <h5 id="change-detail"></h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- /details popup -->

<!-- import modal -->
<div class="modal fade" id="import-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="page-title">
                    <h4>Export Report Sales</h4>
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
<link  href="https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css" rel="stylesheet"/>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>
<script>
    var url_product = "{{ route('product.search') }}";
    var url_save    = "{{ route('sales.store') }}";
    var url_data    = "{{ route('sales.data') }}";
    var url_show    = "{{ route('sales.show',':id') }}";
    var url_export  = "{{ route('sales.export') }}";
</script>
<script>
    $(document).ready(function() {
        let selectedProductIds  = [];
        var selectedFilter = 'last_7_days'; // Default

        var table = $("#table").dataTable({
                ...defaultDatatableSettings,
                processing: true,
                serverSide: true,
                ajax: {
                   url: url_data,
                   data: function (d) {
                       d.date_filter = selectedFilter; // kirim nilai dropdown
                   }
               },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'invoice_number',
                        name: 'invoice_number',
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date',
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

                            actions += `</div>`;
                            return actions;
                        }
                    },
                ],
                columnDefs: [
                    {
                        targets: 4,
                        className: 'action-table-data',
                    }
                ]
            });
        $('#product-search').on('input', function () {
            const keyword = $(this).val();

            if (keyword.length < 2) return;

            $.get(url_product, { q: keyword }, function (products) {
                let dropdown = $('<ul class="list-group position-absolute w-100 z-3"></ul>');
                products.forEach(product => {
                    dropdown.append(`<li class="list-group-item list-item" data-id="${product.id}" data-name="${product.name}" data-price="${product.selling_price}">
                        ${product.name} - Rp ${product.selling_price}
                    </li>`);
                });

                $('.autocomplete-result').remove();
                $('#product-search').after(dropdown.addClass('autocomplete-result'));
            });
        });

        $(document).on('click', '.list-item', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const price = parseFloat($(this).data('price'));

            if (selectedProductIds.includes(id)) {
                showAlert('Product already selected.','Opps...','info');
                return;
            }

            selectedProductIds.push(id);

            const row = `
                <tr data-id="${id}">
                    <td>
                        ${name}
                        <input type="hidden" name="product_id[]" value="${id}">
                    </td>
                    <td>
                        <input type="number" name="qty[]" value="1" class="form-control qty-input" data-price="${price}">
                    </td>
                    <td>
                        ${formatRupiah(price)}
                         <input type="hidden" name="price[]" value="${price}">
                    </td>
                    <td class="total-cell">${formatRupiah(price)}</td>
                </tr>
            `;

            $('#product-table tbody').append(row);
            $('.autocomplete-result').remove();
            $('#product-search').val('');

            calculateGrandTotal();
        });

        $(document).on('input', '.qty-input', function () {
            const qty = parseInt($(this).val());
            const price = parseFloat($(this).data('price'));
            const total = qty * price;

            $(this).closest('tr').find('.total-cell').text(formatRupiah(total));
            calculateGrandTotal();
        });

        function calculateGrandTotal() {
            let grandTotal = 0;
            $('.total-cell').each(function () {
                // const amount = parseFloat($(this).text().replace('Rp', '').replace(',', '').trim());
                const amount = parseFloat($(this).text().replace(/[^\d]/g, ''));
                grandTotal += amount;
            });

            $('#grand-total').text(formatRupiah(grandTotal));
            $("#total-purchase").val(formatRupiah(grandTotal));

        }

        function formatRupiah(angka) {
            let number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }

        function parseRupiah(rupiah) {
            return parseInt(rupiah.replace(/[^0-9]/g, '')) || 0;
        }

        function calculateChange() {
            const cash = parseRupiah($('#cash-input').val());
            const total = parseRupiah($('#total-purchase').val());
            const change = cash - total;
            $('#change-output').val(change >= 0 ? formatRupiah(change) : 'Rp 0');
        }

        $('#cash-input').on('input', function () {
            let value = $(this).val();
            $(this).val(formatRupiah(value));
            calculateChange();
        });

        $("#formSales").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url : url_save,
                type : "POST",
                data : $(this).serialize(),
                beforeSend: function() {
                    showLoadingAlert();
                },success : function(response) {
                    $('#product-table tbody').empty();
                    $('#formSales')[0].reset();
                    selectedProductIds = [];

                    // Reset grand total
                    $('#grand-total').text('Rp 0');

                    // Tampilkan notifikasi atau tutup modal
                    $('#add-sales-new').modal('hide');
                    table.DataTable().ajax.reload(); // reload data table dengan filter baru
                    showAlert(response.message,response.title,'success');
                },error : function(xhr) {
                    showAlert(xhr.responseJSON.message,'Error','error');
                }
            })
        });

        $('.sort-option').on('click', function () {
            selectedFilter = $(this).data('sort'); // ambil filter dari dropdown
            table.DataTable().ajax.reload(); // reload data table dengan filter baru
        });

        $(document).on('click','.show_data',function() {
            var id = $(this).data('id');
            var url = url_show.replace(":id",id);
            $.ajax({
                url : url,
                type: "GET",
                beforeSend: function() {
                    showLoadingAlert();
                },success: function(response) {
                    var data = response.data;
                    $("#invoice_number").html(data.invoice_number);
                    $("#transaction_date").html(data.transaction_date);

                    const details = response.data.details;
                    const $tbody = $('.table.datanew tbody');
                    $tbody.empty(); // Kosongkan isi table terlebih dahulu

                    details.forEach(detail => {
                         const row = `
                             <tr>
                                 <td>${detail.product_name}</td>
                                 <td>${detail.quantity}</td>
                                 <td>${detail.price}</td>
                                 <td>${detail.subtotal}</td>
                             </tr>
                         `;
                         $tbody.append(row);
                     });

                     $("#grand-total-detail").html(data.total_amount);
                     $("#amount-paid-detail").html(data.amount_paid);
                     $("#change-detail").html(data.change);
                    $("#sales-details-new").modal('show');
                    swal.close();
                },error : function(xhr) {
                    var message = xhr.responseJSON.message;
                    var title  = "Error";
                    showAlert(message,title,'error');
                }
            })
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
