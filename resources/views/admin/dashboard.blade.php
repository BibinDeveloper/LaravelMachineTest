
@extends('admin.layout')

@section('title','Dashboard')


@section('content')

					<div class="container py-5">
    <div class="row g-4">
        
        <!-- Stat Card 1 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-4 p-3 me-3">
                        <!-- Bootstrap Icon / FontAwesome -->
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 text-uppercase fs-7 fw-semibold">Total Active Products</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $total_active_products }}</h3>
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-4 p-3 me-3">
                        <i class="bi bi-currency-dollar fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 text-uppercase fs-7 fw-semibold">Total Low Stock Products</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $total_low_stock_products}}</h3>
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-4 p-3 me-3">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1 text-uppercase fs-7 fw-semibold">Total Expenditure</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $total_expenditure }}</h3>
                       
                    </div>
                </div>
            </div>
        </div>

    </div>

<br><br>

    <div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									
    <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light text-uppercase fs-7 text-muted fw-semibold">
                    <tr>
                        <th class="py-3 ps-4">#</th>
                        <th class="py-3">Supplier</th>
                        <th class="py-3">Amount Payable</th>
                        <th class="py-3">Status</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->


                    @if(count($latest_orders)>0)

                    @foreach($latest_orders as $order)
                    <tr>
                        <td class="ps-4">
                           {{ $order->order_id}}
                        </td>
                        <td>{{ $order->supplier->name }}</td>
                        <td>{{ $order->amount_payable }}</td>
                        <td class="text-muted small">{{ $order->status->status_name }}</td>
                      
                    </tr>

                    @endforeach

                    @else


                     <tr>
                        <td class="ps-4" colspan="4">
                             No Orders
                        </td>
                       
                    </tr>


                    @endif



  

                </tbody>
            </table>
</div>

                                </div></div></div>

@endsection