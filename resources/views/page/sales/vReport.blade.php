@extends('vMaster')
@section('title','Sales Report')
@section('content')
<div class="page-header">
    <div class="add-item d-flex">
        <div class="page-title">
            <h4>Sales report</h4>
            <h6>Manage your Sales report</h6>
        </div>
    </div>
    <ul class="table-top-head">
        <li class="me-2">
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i class="ti ti-refresh"></i></a>
        </li>
        <li>
            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i class="ti ti-chevron-up"></i></a>
        </li>
    </ul>
</div>
<div class="card">
    <div class="card-body pb-1">
        <form id="generate_report">
            <div class="row align-items-end">
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control date-range" name="start_date" id="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control date-range" name="end_date" id="end_date" required>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="mb-3">
                        <button class="btn btn-primary w-100" type="submit">Generate Report</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- /product list -->
<div class="card no-search">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
        <div>
            <h4>Sales Report</h4>
        </div>
        <ul class="table-top-head">
           <li class="me-2">
               <a id="btn-excel" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Excel">
                   <img src="{{ asset('assets/img/icons/excel.svg') }}" alt="excel">
               </a>
           </li>
       </ul>

    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table datatable" id="table_report">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Code</th>
                        <th>Category</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                         <td colspan="8" class="text-center">Please do a search first</td>
                     </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" style="text-align:right;"><strong>Grand Total:</strong></td>
                        <td id="grand-total"><strong>Rp 0</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<!-- /product list -->
@endsection
@push('js')
<script>
    let url_search  = "{{ route('sales.report.search') }}";
    let url_excel   = "{{ route('sales.report.excel') }}";
</script>
<script>
    $(document).ready(function() {
        $("#generate_report").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url : url_search,
                data: $(this).serialize(),
                type: "POST",
                beforeSend: function() {
                    showLoadingAlert();
                },success: function(response) {
                    const data = response.data;
                    let tbody = $('.datatable tbody');
                    tbody.empty();
                    if (data.length === 0) {
                        // Jika data kosong, tampilkan pesan
                        tbody.append(`
                            <tr>
                                <td colspan="8" class="text-center">Data not available</td>
                            </tr>
                        `);
                        // Kosongkan grand total
                        $('#grand-total').html(`<strong>Rp 0</strong>`);
                        // Nonaktifkan tombol export
                        $('#btn-excel').addClass('disabled');
                        showAlert('Data not available','Opps','info');

                        return;
                    }
                    data.forEach((item, index) => {
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.created_at || '-'}</td>
                                <td>${item.product_code || '-'}</td>
                                <td>${item.category || '-'}</td>
                                <td>${item.product_name}</td>
                                <td>${item.qty || '-'}</td>
                                <td>${item.price || '-'}</td>
                                <td>${item.total || '-'}</td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                    $('#grand-total').html(`<strong>${response.grand_total}</strong>`);


                    // Aktifkan tombol export dengan parameter pencarian
                    let start = $('#start_date').val();
                    let end = $('#end_date').val();

                    $('#btn-excel').attr('href', `/export/excel?start_date=${start}&end_date=${end}`).removeClass('disabled');
                    showAlert('Data successfully found','Success','success');
                },error : function(xhr) {
                    var message = xhr.responseJSON.message;
                    showAlert(message,'Error','errpr');
                }
            })
        });

        $(document).on('click', '#btn-excel', function(e) {
            e.preventDefault();

            let start = $('#start_date').val();
            let end = $('#end_date').val();

            if (!start || !end) {
                showAlert('Please fill in the start and end date.','Opps','info');
                return;
            }

            let url = url_excel + `?start_date=${start}&end_date=${end}`;
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    start_date : start,
                    end_date  : end
                },
                xhrFields: {
                    responseType: 'blob', // Mengatur respons sebagai file
                },
                beforeSend: function () {
                    showLoadingAlert(); // Fungsi untuk menampilkan loading
                },
                success: function (blob, status, xhr) {
                    // Ambil nama file dari header 'Content-Disposition'
                    let disposition = xhr.getResponseHeader('Content-Disposition');
                    let filename = disposition && disposition.match(/filename="(.+)"/)
                                   ? disposition.match(/filename="(.+)"/)[1]
                                   : 'download.xls';

                    // Buat link untuk mengunduh file
                    let downloadLink = document.createElement('a');
                    let url = window.URL.createObjectURL(blob);
                    downloadLink.href = url;
                    downloadLink.download = filename;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);

                    // Tampilkan alert sukses
                    showAlert('File successfully downloaded!','Sukses','success');

                },
                error: function (xhr) {
                    let message = xhr.responseJSON?.message || 'Something Wrong.';
                    showAlert(message,'Error','error');
                },
            });
        });
    });
</script>
@endpush
