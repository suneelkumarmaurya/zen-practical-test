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

    <section class="content-header">
        <div class="container-fluid my-2">
            {{--  @include('admin.message')  --}}
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h1>Products list</h1>
                </div>
                <div class="col-sm-2 text-right">
                    <a href="{{route('product.form')}}" class="btn btn-primary">New Product</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card mt-5">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Rate</th>
                                <th>Unit</th>
                                <th>Qty</th>
                                <th>Disc%</th>
                                <th>Net Amt.</th>
                                <th>Total Amt.</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($tempDatas as $tempData)
                            <?php
                                $net_amt_id = "net_amt_$tempData->product_id";
                                $total_amt_id = "total_amt_$tempData->product_id";
                                $qty_id = "qty_$tempData->product_id";
                                $percenatge_id = "percentage_$tempData->product_id";
                                $rate_id = "rate_$tempData->product_id";
                                $unit_id = "unit_$tempData->product_id";
                                $pro_id = "pro_$tempData->product_id";
                            ?>
                            <tr>
                                <td>
                                    <select data-id="{{$tempData->product_id}}" id="{{$pro_id}}" name="product" class="form-control product">
                                        <option value="">Select Product</option>
                                        @foreach ($products as $product)
                                        <option value="{{$product->product_id}}" {{($product->product_id==$tempData->temp_product_id) ? 'selected':''}}>{{$product->product_name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="{{$rate_id}}">{{$tempData->rate}}</td>
                                <td id="{{$unit_id}}">{{$tempData->unit}}</td>
                                <td ><input type="number" name="qty"  id="{{$qty_id}}" value="{{$tempData->qty}}" onblur="chagePriceData({{$tempData->product_id}})">
                                   </td>
                                <td id="disc"> <input type="number" name="disc" id="{{$percenatge_id}}" value="{{$tempData->disc}}" onblur="chagePriceData({{$tempData->product_id}})"></td>
                                <td id="{{$net_amt_id}}">{{$tempData->net_amt}}</td>
                                <td id="{{$total_amt_id}}">{{$tempData->total_amt}}</td>
                                <td>
                                    <a href="javascript:void()" onclick="deleteProduct({{$tempData->product_id}})" class="text-danger w-4 h-4 mr-1">X REMOVE</a>
                                </td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                @if(count($tempDatas) > 0)
                <div class="card-footer clearfix">
                    <button  type="submit" class="btn btn-primary " id="dataStore">Submit</button>


                </div>
                @endif
            </div>
        </div>
        <!-- /.card -->
    </section>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>

        function chagePriceData(tempProdId) {
            var net_amt_id = `net_amt_${tempProdId}`;
            var total_amt_id = `total_amt_${tempProdId}`;
            var qty_id = `qty_${tempProdId}`;
            var percenatage_id = `percentage_${tempProdId}`;
            var rate_id = `rate_${tempProdId}`;

            var qty = $(`#${qty_id}`).val();
            var percentage = $(`#${percenatage_id}`).val();
            var rate = $(`#${rate_id}`).text();


            var amt = calculatePercentage(rate, percentage);
            $(`#${net_amt_id}`).html(amt);
            $(`#${total_amt_id}`).html(qty * amt);
            updateDatabase(tempProdId);
        }

        function updateDatabase(tempDataId){

            var qty = $(`#qty_${tempDataId}`).val();
            var percentage = $(`#percentage_${tempDataId}`).val();
            var pro_id = $(`#pro_${tempDataId}`).val();
            $.ajax({
                url:"{{route('product.add')}}",
                type:"post",
                datatype:"json",
                data:{
                    tmpId: tempDataId,
                    disc: percentage ,
                    qty: qty,
                    product: pro_id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(res){
                    if (res.status==true) {

                        {{--  $('#total_Amt').html(res.totalAmount);  --}}
                    }
                }
            })
        }

        $('.product').change(function(){
            var productId=$(this).val();
            var tempProdId = $(this).attr('data-id');

            var net_amt_id = `net_amt_${tempProdId}`;
            var total_amt_id = `total_amt_${tempProdId}`;
            var qty_id = `qty_${tempProdId}`;
            var percenatage_id = `percentage_${tempProdId}`;
            var rate_id = `rate_${tempProdId}`;
            var unit_id = `unit_${tempProdId}`;

            var qty = $(`#${qty_id}`).val();
            var percentage = $(`#${percenatage_id}`).val();
            $.ajax({
                url:"{{route('product.change')}}",
                type:'post',
                data:{productId:productId},
                datatype:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){
                    console.log(response.product);
                  if(response.status==true) {
                    updateDatabase(tempProdId);
                    var productData=response.product;
                    $(`#${rate_id}`).html(productData.rate);
                    $(`#${unit_id}`).html(productData.unit);
                    var amt = calculatePercentage(productData.rate, percentage);
                    $(`#${net_amt_id}`).html(amt);
                    $(`#${total_amt_id}`).html(qty * amt);


                  }

                }
            });
        })

        function calculatePercentage(netAmount, percentage){
            return netAmount - (netAmount*percentage/100)
        }

        $('#dataStore').click(function(e){
            e.preventDefault();
            $.ajax({
                url:"{{route('product.dataSave')}}",
                type:"get",
                datatype:'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response){

                    if(response.status) {
                        window.location.href = '{{route("product.form")}}'
                    }

                  }

                })

            });

            function deleteProduct(tempDataId){
                var isCOnfirm = confirm('Are you sure ?')
                if(!isCOnfirm) {
                    return;
                }

                $.ajax({
                    url:"{{route('product.delete')}}",
                    type:"post",
                    datatype:"json",
                    data:{
                        tmpId: tempDataId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(res){
                        if (res.status==true) {
                            alert('Product deleted successfully')
                            location.reload();

                        } else {
                            alert('Unable to delete product')
                        }
                    }
                })
            }

    </script>
</body>
</html>
