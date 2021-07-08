<table>
    <thead>
    <tr>
        <th>Seller Details</th>
    </tr>
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone Number</th>
        <th scope="col">Aadhaar Number</th>
        <th scope="col">Pan Number</th>
        <th scope="col">GST Number</th>
        <th scope="col">Cheque number</th>
        <th scope="col">Address</th>
        <th scope="col">Pincode</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $seller->name }}</td>
            <td>{{ $seller->email }}</td>
            <td>{{ $seller->phone_number }}</td>
            <td>{{ $seller->aadhaar_number }}</td>
            <td>{{ $seller->pan_number}}</td>
            <td>{{ $seller->gst_number}}</td>
            <td>{{ $seller->cheque_number}}</td>
            <td>{{ $seller->address}}</td>
            <td>{{ $seller->pincode}}</td>
        </tr>
    </tbody>
</table>

<table>
    <thead><tr><th>Orders</th></tr></thead>
    <thead>
        <tr>
            <th>Order status</th>
            <th>Due date</th>
            <th>Stock availablity</th>
            <th>Brand</th>
            <th>Model</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Made in India</th>
            <th>Is active</th>
            <th>Delivery method</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seller->orders as $order)
            {{-- {{ dd($order->toArray()) }} --}}
            <tr>
                <td>{{ config('smartebiz.order_status')[$order->order_status] }}</td>
                <td>{{ date('d-m-Y', strtotime($order->due_date))}}</td>
                <td>{{ $order->stock_availablity ? 'Available' : 'Not available' }}</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->item_detail->brand_name}} @endif</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->item_detail->model }} @endif</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->quantity }} @endif</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->price }} @endif</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->is_made_in_india ? 'Yes' : 'No' }} @endif</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->is_item_active ? 'Yes' : 'No' }} @endif</td>
                <td>@if( $order->items->count() > 0 ) {{ $order->items[0]->delivery_method ? 'Inhouse' : 'Partner' }} @endif</td>
            </tr>
        @endforeach        
    </tbody>
</table>
{{-- {{ dd(1) }} --}}
