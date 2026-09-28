@extends('admin.layout')

@section('title','Add purchase order')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                
                <!-- Card Header -->
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Create Purchase Order</h4>
                        <p class="text-muted small mb-0">Add supplier details and select products to order.</p>
                    </div>
                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>

                <div class="card-body p-4">
                    <form id="purchaseOrderForm">
                        
                        <!-- Supplier & Date Section -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Supplier Name</label>
                                <select class="form-select bg-light border-0 py-2" name="supplier" required>
                                    <option value="" selected disabled>Select supplier...</option>
                                    @foreach($suppliers as $supplier)

                                       <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>

                                    @endforeach
                                </select>
                            </div>
                           
                          
                        </div>

                        <hr class="text-muted opacity-25 my-4">

                        <!-- Products Section Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0 fs-6 text-uppercase tracking-wider">Order Items</h5>
                            <button type="button" id="add_product" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="bi bi-plus-lg me-1"></i> Add Product
                            </button>
                        </div>

                        <!-- Dynamic Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table align-middle mb-0" id="orderItemsTable">
                                <thead class="bg-light text-uppercase fs-7 text-muted fw-semibold">
                                    <tr>
                                        <th class="py-3 ps-3" style="width: 40%;">Product</th>
                                        <th class="py-3" style="width: 15%;">Unit Price ($)</th>
                                        <th class="py-3" style="width: 15%;">Quantity</th>
                                        <th class="py-3" style="width: 20%;">Total ($)</th>
                                        <th class="py-3 text-end pe-3" style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="rows">
                                    <!-- Default Initial Row -->
                                    <tr>
                                        <td class="ps-3">
                                            <select class="form-select bg-light border-0 product-select">
                                                <option value="" selected disabled>Choose product...</option>
                                                <option value="101" data-price="15.00">Wireless Mouse - $15.00</option>
                                                <option value="102" data-price="45.50">Mechanical Keyboard - $45.50</option>
                                                <option value="103" data-price="120.00">27" Monitor - $120.00</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control bg-light border-0 unit-price" value="0.00" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control bg-light border-0 qty-input" value="1" min="1">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control bg-light border-0 item-total" value="0.00" readonly>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" class="btn btn-light btn-sm text-danger rounded-circle p-2 remove-row" title="Remove">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Calculation Area -->
                        <div class="row justify-content-end mb-4">
                            <div class="col-md-5">
                                <div class="p-3 bg-light rounded-4">
                                    <div class="d-flex justify-content-between mb-2 text-muted small">
                                        <span>Subtotal:</span>
                                        <span id="grandSubtotal" class="fw-bold text-dark">0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 text-muted small">
                                        <span>Tax (10%):</span>
                                        <span id="grandTax" class="fw-bold text-dark">0.00</span>
                                    </div>
                                    <hr class="text-muted opacity-25">
                                    <div class="d-flex justify-content-between text-dark">
                                        <span class="fw-bold">Grand Total:</span>
                                        <span id="grandTotal" class="fw-bold fs-5 text-primary">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-light rounded-pill px-4">Cancel</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Save Purchase Order</button>
                        </div>

                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">




@endsection

@push('scripts')

<script
  src="https://code.jquery.com/jquery-4.0.0.js"
  integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U="
  crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <script>

      $(document).ready(function()
    {

     window.sub_total=0;
     window.grand_total=0;

     window.grand_gst=0;

         $.ajax({

           headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
           url:"{{ route('addRow') }}",
           type:"POST",
           dataType:"JSON",
           success:function(response)
           {
                if(response.status=="success")
                {
                    $("#rows").html(response.content);
                }
           }
         });

        $(document).on("click","#add_product",function()
    {
         $.ajax({

           headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
           url:"{{ route('addRow') }}",
           type:"POST",
           dataType:"JSON",
           success:function(response)
           {
                if(response.status=="success")
                {
                    $("#rows").append(response.content);
                }
           }
         });
    });

       $(document).on("change","select[name='product[]']",function()
        {

       var  lockedValue = $(this).val();

       $(this).on('mousedown keydown', function(e) {
            e.preventDefault();
        });

          var value=$(this).val();
            var row=$(this).closest('tr');


            $.ajax({
                headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
                url:"{{ route('loadProduct') }}",
                type:"POST",
                data:{product_id:value},
                dataType:"JSON",
                success:function(response)
                {
                    if(response.status=="success")
                    {
                         row.find("input[name='unit_price[]']").val(response.product.unit_price);

                          row.find("input[name='qty[]']").val(1);


                          row.find("input[name='item_total[]']").val(response.product.unit_price);

                         

                         window.sub_total=window.sub_total+response.product.unit_price;

                         $("#grandSubtotal").html(window.sub_total);

                         window.grand_gst=window.grand_gst+(window.sub_total*10/100);

                          $("#grandTax").html(window.grand_gst);

                          window.grand_total=window.grand_total+window.grand_gst+window.sub_total;

                          $("#grandTotal").html(window.grand_total);




                    }
                }
            });

           



        });


        $(document).on("click",".remove-row",function()
        {

            var row=$(this).closest('tr');

            var item_total=row.find("input[name='item_total[]']").val();

            window.sub_total=window.sub_total-item_total;

            $("#grandSubtotal").html(window.sub_total);

            window.grand_gst=window.sub_total*(10/100);

            $("#grandTax").html(window.grand_gst);

             $("#grandTotal").html((window.grand_gst+window.sub_total));



            $(this).parent().parent().remove();


            
        });

        
        $(document).on("submit","#purchaseOrderForm",function(e)
    {

       e.preventDefault();

         var form_data=new FormData($(this)[0]);

          $.ajax({
            headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
            url:"{{ route('savePurchaseOrder') }}",
            type:"POST",
            data:form_data,
            dataType:"JSON",
            processData:false,
            contentType:false,
            success:function(response)
            {

                if(response.status=="error")
                {

                    swal({
                        title:"Error",text:response.message,'icon':"error"
                    });

                }

                else if(response.status=="success")
                {

                    swal({
                        title:"Success",text:response.message,'icon':"success"
                    });

                    location.reload();


                }

            },
            error:function(xhr)
            {
               
                    swal({
                        title:"Error",text:xhr.responseJSON.message,'icon':"error"
                    });
            }
          });

    });
         
    });

    </script>


@endpush