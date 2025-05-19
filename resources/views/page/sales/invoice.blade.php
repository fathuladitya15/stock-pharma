<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 40px;
      background: #f7f7f7;
      color: #333;
    }

    .invoice-container {
      background: #fff;
      padding: 40px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .invoice-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #ddd;
      padding-bottom: 20px;
    }

    .invoice-header img {
      width: 120px;
    }

    .invoice-title {
      text-align: right;
    }

    .invoice-title h1 {
      margin: 0;
      font-size: 28px;
      color: #444;
    }

    .invoice-info {
      margin-top: 30px;
      margin-bottom: 20px;
    }

    .invoice-info table {
      width: 100%;
      font-size: 14px;
    }

    .invoice-info td {
      padding: 4px 0;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
    }

    .items-table th, .items-table td {
      border: 1px solid #e0e0e0;
      padding: 12px;
      text-align: left;
    }

    .items-table th {
      background-color: #f1f1f1;
      font-weight: bold;
    }

    .totals {
      margin-top: 30px;
      width: 100%;
      max-width: 400px;
      float: right;
    }

    .totals table {
      width: 100%;
    }

    .totals td {
      padding: 8px;
    }

    .totals tr:last-child td {
      font-size: 16px;
      font-weight: bold;
      border-top: 2px solid #ccc;
    }

    .signature-section {
      clear: both;
      margin-top: 80px;
      display: flex;
      justify-content: space-between;
    }

    .signature-box {
      text-align: center;
      width: 200px;
    }

    .signature-box p {
      margin-top: 60px;
      border-top: 1px solid #333;
      padding-top: 4px;
    }
  </style>
</head>
<body>
  <div class="invoice-container">
    <div class="invoice-header">
      <img src="{{ asset('image/logo/StockPharma-Logo.png') }}" alt="Company Logo">
      <div class="invoice-title">
        <h1>INVOICE</h1>
        <p>No: <strong>{{ $sales->invoice_number }}</strong><br>
           Date: <strong>{{ Carbon\Carbon::parse($sales->transaction_date)->format('l, d F Y, H:i') }}</strong></p>
      </div>
    </div>

    <table class="items-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Unit Price</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($sales->details as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->subtotal }}</td>
            </tr>
        @endforeach
      </tbody>
    </table>

    <div class="totals">
      <table>
        <tr>
          <td>Total:</td>
          <td style="text-align:right"><strong>{{ $sales->total_amount }}</strong></td>
        </tr>
        <tr>
          <td>Paid:</td>
          <td style="text-align:right">{{ $sales->amount_paid }}</td>
        </tr>
        <tr>
          <td>Change:</td>
          <td style="text-align:right">{{ $sales->change }}</td>
        </tr>
      </table>
    </div>

    <div class="signature-section">

    </div>
  </div>
</body>
</html>
