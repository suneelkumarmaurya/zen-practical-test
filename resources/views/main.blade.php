<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

    <div class="container mt-5">

        <h2>Add New Product</h2>

        <form action="{{route('product.add')}}" method="post" >
            @csrf
            <div class="row mb-3">
              <label for="inputEmail3" class="col-sm-2 col-form-label">Customer name:</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="inputEmail3" name="customer_name">
              </div>
            </div>
            {{--  @if ($products->isNotEmpty())  --}}


                <div class="row mb-3">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">select product :</label>
                  <div class="col-sm-4">
                    <select name="product" id="product" class="form-control">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                        <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Rate:</label>
                    <div class="col-sm-4">
                     <input type="text" value="" name="rate" class="form-control" id="rate" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Unit:</label>
                    <div class="col-sm-4">
                     <input type="text" value="" name="unit" class="form-control"  id="unit" readonly>
                    </div>
                </div>

            {{--  @endif  --}}
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">QTY:</label>
                <div class="col-sm-4">
                 <input type="number" name="qty" class="form-control" id="qty" onblur="qtyChange(this)">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label">DISC %:</label>
                <div class="col-sm-4">
                 <input type="number" name="disc" class="form-control" id="disc" onblur="discountPrice(this)">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label"> Net Amount:</label>
                <div class="col-sm-4">
                 <input type="number" name="net_amount" class="form-control" id="net_Amt" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputPassword3" class="col-sm-2 col-form-label"> Total Amount:</label>
                <div class="col-sm-4">
                 <input type="number" name="total_amount" class="form-control" id="total_Amt" readonly>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add</button>
          </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        function discountPrice(disc){
            var discPerventage=$(disc).val();
            var rate=$('#rate').val();
            var qty=$('#qty').val();
            var netamt =  rate - (rate*discPerventage/100);
            $('#net_Amt').val(netamt);
            $('#total_Amt').val(netamt*qty);
        }

        // qty change

        function qtyChange(qty){
            var qtyChange=$(qty).val();
            var net_Amt=$('#net_Amt').val();
            $('#total_Amt').val(qtyChange*net_Amt);

        }


        $('#product').change(function(){
            var productId=$(this).val();

            $.ajax({
                url:"{{route('product.change')}}",
                type:'post',
                data:{productId:productId},
                datatype:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                  if(response.status==true) {
                    var productData=response.product;
                        $('#rate').val(productData.rate);
                        $('#unit').val(productData.unit);
                  }

                }
            });
        })
    </script>
</body>
</html>
