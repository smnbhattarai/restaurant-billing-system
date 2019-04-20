@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card text-dark bg-light">
                    <div class="card-header">Available Items</div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Enter item name to search</span>
                                    </div>
                                    <input type="text" class="form-control" id="search-item">
                                </div>
                            </div>

                            <div class="col-md-4 text-right">
                                <a href="{{ route('menu.create') }}" class="btn btn-outline-primary btn-block">Add Menu Item</a>
                            </div>
                        </div>

                        <table class="table table-borderless table-striped" id="item-table">
                            <tr>
                                <th>S.No.</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Added</th>
                                <th>Edited</th>
                                <th></th>
                            </tr>

                            @foreach($menu_items as $k => $item)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $item->menu_code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>Rs. {{ $item->price }}</td>
                                    <td>
                                        @if($item->item_photo != NULL)
                                            <img src="{{ url('images') . '/' . $item->item_photo }}" alt="{{ $item->name }}" width="75" class="img-fluid">
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
                                    <td>{{ $item->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('menu.edit', $item->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <form action="{{ route('menu.destroy', $item->id) }}" method="post" style="display: inline;" class="item-del-form">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm menu-delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                        </table>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>

        var deleteItems = document.querySelectorAll('.item-del-form');
        for(var i = 0; i < deleteItems.length; i++) {
            deleteItems[i].addEventListener('submit', function(e) {
                var form = this;
                e.preventDefault();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this menu item!",
                    icon: "warning",
                    buttons: [
                        'No, cancel it!',
                        'Yes, Delete it!'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        form.submit();
                    } else {
                        swal("Cancelled", "Menu item not deleted!", "error");
                    }
                })
            });
        }


        $(document).ready(function(){
            $("#search-item").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#item-table tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });

    </script>
@endsection
