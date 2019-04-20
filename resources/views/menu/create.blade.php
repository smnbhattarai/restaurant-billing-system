@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-8">
                <div class="card text-dark bg-light">
                    <div class="card-header">Add Menu Item</div>

                    <div class="card-body">
                        <form action="{{ route('menu.store') }}" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Food Item Name</label>
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="price">Price (Rs.)</label>
                                <input id="price" type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" name="price" value="{{ old('price') }}">
                                @if($errors->has('price'))
                                    <div class="invalid-feedback">{{ $errors->first('price') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="item_photo">Item Image</label>
                                <input id="item_photo" type="file" class="form-control-file{{ $errors->has('item_photo') ? ' is-invalid' : '' }}" name="item_photo" value="">
                                @if($errors->has('item_photo'))
                                    <div class="invalid-feedback">{{ $errors->first('item_photo') }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg mt-5">Add Menu Item</button>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-header">Similar item(s)</div>
                    <div class="card-body similar-item">

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#name").on('keyup', function(){
                var item_input = $("#name").val();

                // Only send request if input character is more than 2
                if(item_input.length > 2) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('menu.suggestion') }}",
                        data: { menu_item: item_input },
                        dataType: "json",
                        success: function(data) {
                            // loop through the response and display
                            var html = '';
                            for(let i = 0; i < data.length; i++) {
                                html += '<p><strong>'+ data[i].name +'</strong> - Rs. ' + data[i].price + '</p>';
                            }
                            $(".similar-item").html(html);
                        },
                    });
                }

            });
        });
    </script>
@endsection
