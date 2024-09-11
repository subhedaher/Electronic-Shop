<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            
        }
    </style>
</head>

<body>
    <h3>{{ Config::get('app.name') }}</h3>
    <h3>{{ date('Y-m-d') }}</h3>
    <hr>
    <h4>User Detailes</h4>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->user->first_name }}</td>
                <td>{{ $order->user->last_name }}</td>
                <td>{{ $order->user->email }}</td>
                <td>{{ $order->user->phone_number }}</td>
            </tr>
        </tbody>
    </table>
    <h4>Order Detailes</h4>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Total</th>
                <th>Payment Method</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->status }}</td>
                <td>${{ $order->total }}.00</td>
                <td>{{ $order->payment_method }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        </tbody>
    </table>
    <h4>Products</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->order_detailes as $product)
                <tr>
                    <td>{{ ++$loop->index }}</td>
                    <td>{{ $product->product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>${{ $product->price }}.00</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3">Tax </td>
                <td>$1.00 </td>
            </tr>
        </tbody>
    </table>
    <h4>Shipping Address Detailes</h4>
    <table>
        <thead>
            <tr>
                <th>Country</th>
                <th>City</th>
                <th>Post Code</th>
                <th>Address Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->shipping_address->country }}</td>
                <td>{{ $order->shipping_address->city }}</td>
                <td>{{ $order->shipping_address->post_code }}</td>
                <td>{{ $order->shipping_address->address_details }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
