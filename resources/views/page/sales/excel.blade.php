<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        td, th {
            border: 1px solid #000;
            padding: 5px;
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <h3>Data Sales</h3>
    <table>
        <thead>
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
            @php $no = 1; $total = 0; @endphp
            @foreach ($result as $item)
                @php
                    $subtotal = (int)$item['qty'] * (int)$item['price'];
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item['created_at'] }}</td>
                    <td>{{ $item['product_code'] }}</td>
                    <td>{{ $item['category'] }}</td>
                    <td>{{ $item['product_name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>{{ $item['total'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7"><strong>Total</strong></td>
                <td><strong>{{ $grandTotalToRP }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
