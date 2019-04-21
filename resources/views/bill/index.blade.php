@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <table class="table table-striped table-borderless table-sm">
                    <tr>
                        <th>Menu Item</th>
                        <th>Customer Name</th>
                        <th>Quantity</th>
                        <th>Menu Price</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Final Price</th>
                        <th>Date/Time</th>
                    </tr>

                    @foreach($bills as $bill)
                        <tr>
                            <td>{{ $bill->menu->name }}</td>
                            <td>{{ $bill->customer_name }}</td>
                            <td>{{ $bill->quantity }}</td>
                            <td>{{ $bill->menu_price }}</td>
                            <td>{{ $bill->discount }}</td>
                            <td>{{ $bill->tax }}</td>
                            <td>{{ $bill->final_price }}</td>
                            <td>{{ $bill->created_at->format("Y-m-d h:i A") }}</td>
                        </tr>
                    @endforeach
                </table>

                {{ $bills->links() }}
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $("#search-item").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#item-table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

        }); // document.ready
    </script>
@endsection
