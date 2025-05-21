<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase Order</title>
  <style>
    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 12px;
      margin: 40px;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header h1 {
      margin: 0;
      font-size: 24px;
    }

    .company-info, .supplier-info {
      margin-bottom: 20px;
    }

    .po-info {
      margin-bottom: 20px;
    }

    .po-info table, .supplier-info table {
      width: 100%;
      border-collapse: collapse;
    }

    .po-info td, .supplier-info td {
      padding: 5px;
    }

    table.items {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table.items th, table.items td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }

    table.items th {
      background-color: #f5f5f5;
    }

    .totals {
      margin-top: 20px;
      width: 100%;
    }

    .totals td {
      padding: 5px;
    }

    .totals .label {
      text-align: right;
      font-weight: bold;
    }

    .signature {
      margin-top: 50px;
      width: 100%;
    }

    .signature td {
      padding-top: 50px;
      text-align: center;
    }

    .footer {
      position: fixed;
      bottom: 20px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 10px;
      color: #aaa;
    }
  </style>
</head>
<body>

  <div class="header">
    <h1>PURCHASE ORDER</h1>
  </div>

  <div class="po-info">
    <table>
      <tr>
        <td><strong>PO Number:</strong> {{ $data->po_number }}</td>
        <td><strong>Date:</strong> {{ $data->order_date }}</td>
      </tr>
    </table>
  </div>

  <div class="supplier-info">
    <table>
      <tr>
        <td><strong>Supplier:</strong> {{ Str::title($supplier->name) }}</td>
      </tr>
      <tr>
        <td><strong>Alamat:</strong> {{ $supplier->address }}</td>
      </tr>
    </table>
  </div>

  <table class="items">
    <thead>
      <tr>
        <th>No</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
            $grandTotal = 0;
        @endphp
        @foreach ($item as $itm )
            @php
                $subtotal = $itm->quantity * $itm->price;
                $grandTotal += $subtotal;
            @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $itm->product->name }}</td>
                <td>{{ $itm->quantity }}</td>
                <td>{{ "Rp ".number_format($itm->price,0,',','.') }}</td>
                <td>{{ "Rp ".number_format($subtotal,0,',','.') }}</td>
            </tr>
        @endforeach
    </tbody>
  </table>

  <table class="totals">
    <tr>
      <td class="label" colspan="4">Total</td>
      <td>{{ "Rp ".number_format($grandTotal,0,',','.') }}</td>
    </tr>
  </table>

  <table class="signature">
    <tr>
      <td>Penerima</td>
      <td>Disetujui</td>
    </tr>
    <tr>
      <td>___________________</td>
      <td>___________________</td>
    </tr>
  </table>

  <div class="footer">
    Dokumen ini dicetak secara otomatis dan tidak memerlukan tanda tangan basah.
  </div>

</body>
</html>
