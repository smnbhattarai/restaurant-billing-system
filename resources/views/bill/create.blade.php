@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-8">
                <div class="card text-dark bg-light">
                    <div class="card-header">Create Bill</div>

                    <div class="card-body">
                        <form action="{{ route('bill.store') }}" method="post" id="menu-item-form">

                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Discount(%)</th>
                                    <th>VAT(%)</th>
                                </tr>
                                <tr>
                                    <td><input type="text" name="customer_name" class="form-control"></td>
                                    <td><input type="text" name="discount_per" class="form-control discount_per" value="0"></td>
                                    <td><input type="text" name="vat_per" class="form-control vat_per" value="13"></td>
                                </tr>
                            </table>

                            <table class="table table-borderless table-sm" id="menuItems">
                                <thead>
                                <tr>
                                    <th width="50%">Item name</th>
                                    <th width="">Price</th>
                                    <th width="">Quantity</th>
                                    <th width="">Price</th>
                                    <th width=""></th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th>Total</th>
                                    <td><input type="text" class="form-control items-total" value="0" disabled></td>
                                </tr>
                                <tr>
                                    <th>Discount Amount</th>
                                    <td><input type="text" class="form-control discount-amount" value="0" disabled></td>
                                </tr>
                                <tr>
                                    <th>Taxable Amount</th>
                                    <td><input type="text" class="form-control taxable-amount" value="0" disabled></td>
                                </tr>
                                <tr>
                                    <th>VAT Amount</th>
                                    <td><input type="text" class="form-control vat-amount" value="0" disabled></td>
                                </tr>
                                <tr>
                                    <th>Final Amount</th>
                                    <td><input type="text" class="form-control final-amount" value="0" disabled></td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td><button class="btn btn-warning btn-block calculate-total-btn">Calculate</button></td>
                                </tr>
                            </table>

                            <button type="submit" class="btn btn-primary btn-block btn-lg mt-4 add-bill" disabled>Add Transaction</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-header">Search Menu to add</div>
                    <div class="card-body">
                        <input type="text" id="menu-item-search" placeholder="Search Menu Item" class="form-control mb-4">
                        <div class="similar-item">

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $("#menu-item-search").on('keyup', function(){
                var item_input = $("#menu-item-search").val();

                // Only send request if input character is more than 2
                if(item_input.length > 2) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('bill.suggestion') }}",
                        data: { menu_item: item_input },
                        dataType: "json",
                        success: function(data) {
                            // loop through the response and display
                            var html = '<table class="table table-sm table-borderless">';
                            if(data.length > 0) {
                                html += create_suggestion_html(data);
                            }
                            html += '</table>';
                            $(".similar-item").html(html);
                        },
                    });
                }

            });


            $(document).on("click", ".add-to-bill", function() {
                var item_id = $(this).data('id');
                var item_name = $(this).data('name');
                var item_price = $(this).data('price');
                var records = '';
                records += `<tr class="bill-item-row">
                                    <td><input type="text" class="form-control" value="${item_name}"></td>
                                    <td><input type="text" name="menu_price[]" class="form-control item-price" value="${item_price}"></td>
                                    <td><input type="text" name="quantity[]" class="form-control item-quantity" value="1"></td>
                                    <td><input type="text" name="item_price[]" class="form-control item-total-price" value="${item_price}" disabled></td>
                                    <td>
                                        <a href="#" class="btn btn-danger remove-item-bill"> X </a>
                                        <input type="hidden" name="menu_id[]" value="${item_id}">
                                    </td>
                                </tr>`;

                $("#menuItems").append(records);
            });


            $(document).on("click", ".remove-item-bill", function() {
                $(this).closest('tr').remove();
            });


            // Perform one row calculation for price
            $(document).on('keyup', '.item-price, .item-quantity', function() {
                var row = $(this).closest('tr');
                var item_price = $('.item-price', row),
                    item_quantity = $('.item-quantity', row),
                    total_price = $('.item-total-price', row);

                item_price = parseFloat(item_price.val());
                item_quantity = parseFloat(item_quantity.val());

                if( ! isNaN(item_price) && !isNaN(item_quantity) ) {
                    total_price.val( ( item_price * item_quantity ).toFixed(2) );
                }
            });


            // Calculate bill amount on button press
            $(".calculate-total-btn").on("click", function(e) {
                e.preventDefault();
                var items_total = 0;
                $(".item-total-price").each(function(){
                    items_total += +$(this).val();
                });
                $(".items-total").val(items_total);

                var discount_percent = $(".discount_per").val();
                discount_percent = parseFloat(discount_percent);
                var discount_amount = (items_total * discount_percent / 100);
                $(".discount-amount").val(discount_amount);

                var taxable_amount = items_total - discount_amount;
                $(".taxable-amount").val(taxable_amount);

                var vat_per = $(".vat_per").val();
                var vat_amount = taxable_amount * vat_per / 100;
                $(".vat-amount").val(vat_amount.toFixed(2));

                var final_amount = taxable_amount + vat_amount;
                $(".final-amount").val(final_amount.toFixed(2));

                $(".add-bill").attr("disabled", false);
            });


        }); // document.ready


        // Create html for menu suggestion
        function create_suggestion_html(data) {
            var html = '';
            for(let i = 0; i < data.length; i++) {
                html += `
                        <tr>
                            <td>${data[i].name} - Rs.${data[i].price}</td>
                            <td><a class="btn btn-sm btn-outline-primary add-to-bill" data-id="${data[i].id}" data-name="${data[i].name}" data-price="${data[i].price}">Add</a></td>
                        </tr>`;
            }
            return html;
        }



    </script>
@endsection
