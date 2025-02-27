
@php

    
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid','resourcepath')->first();

    if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
	}else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 8){
        $extend = 'admission.layouts.app2';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 4){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 15){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(Session::get('currentPortal') == 10){
        $extend = 'hr.layouts.app';
    }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(auth()->user()->type == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else{
        if(isset($check_refid->refid)){
			
			if($check_refid->resourcepath == null){
                $extend = 'general.defaultportal.layouts.app';
			}else if($check_refid->refid == 27){
                $extend = 'academiccoor.layouts.app2';
            }else if($check_refid->refid == 22){
                $extend = 'principalcoor.layouts.app2';
            }else if($check_refid->refid == 29){
                $extend = 'idmanagement.layouts.app2';
            }else if($check_refid->refid ==  23){
				$extend = 'clinic.index';
			}elseif($check_refid->refid ==  24){
				$extend = 'clinic_nurse.index';
			}elseif($check_refid->refid ==  25){
				$extend = 'clinic_doctor.index';
			}elseif($check_refid->refid ==  33){
                $extend = 'inventory.layouts.app2';
                
            }else{
                $extend = 'general.defaultportal.layouts.app';
            }
        }else{
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp

@extends($extend)


@section('pagespecificscripts')

@endsection

@section('content')


    <div class="container my-2">
        
        <div class="row mt-2 justify-content-center">
            <!-- Total Purchase -->
            <div class="col-md-3 mt-2">
                <div class="card border-0"
                    style="background: linear-gradient(to right, #4facfe, #00f2fe);
                border-radius: 2rem;">
                    <div class="card-body d-flex align-items-center ">
                        <i class="fas fa-shopping-cart fa-3x me-3" style="color: white;"></i> <!-- Shopping cart icon -->
                        <div>
                            <h5 class="card-title mb-0">Total Purchase</h5>
                            <p class="card-text mt-2" id="totalPurchase"></p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="card-link ">View details</a>
                    </div>
                </div>
            </div>
            <!-- Item -->
            <div class="col-md-3 mt-2">
                <div class="card item-card border-0 rounded-5"
                    style=" background: linear-gradient(to right, #e47c05, #ffd20b); border-radius: 2rem;">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-box fa-3x me-3" style="color: white;"></i>
                        <div>
                            <h5 class="card-title mb-0">Total Item</h5>
                            <p class="card-text" id="totalitem"></p>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <a href="#" class="card-link">View details</a>
                    </div>
                </div>
            </div>
            <!-- Received -->
            <div class="col-md-3 mt-2">
                <div class="card border-0 rounded-5"
                    style=" background: linear-gradient(to right, #ff3cac, #784ba0);
                    border-radius: 2rem;">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-truck fa-3x me-3" style="color: white;"></i>
                        <div>
                            <h5 class="card-title mb-0">Total Received</h5>
                            <p class="card-text" id="totalReceive"></p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="card-link">View details</a>
                    </div>
                </div>
            </div>
            <!-- Total Sales -->
            <div class="col-md-3 mt-2">
                <div class="card border-0 rounded-5"
                    style="background: linear-gradient(to right, #088a51, #63fccb);
                    border-radius: 2rem;">
                    <div class="card-body d-flex align-items-center">
                        <i class="fas fa-chart-line fa-3x me-3" style="color: white;"></i>
                        <div>
                            <h5 class="card-title mb-0">Total Received Cost</h5>
                            <p class="card-text" id="totalReceiveMoney" ></p>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="card-link">View details</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card border-0" style="background-color: #f4f5f5;">
                    <div class="card-body" style="color: black">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="stock-tab" data-toggle="tab" href="#stock" role="tab"
                                    aria-controls="stock" aria-selected="false"style="color: #000000;">Department</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="stock" role="tabpanel"
                                aria-labelledby="sales-tab">
                                <div class="container">
                                    <div class="table-responsive table-sm table-info"
                                        style="max-height: 120px; overflow-y: auto;">
                                        <table class="table table-striped">
                                            <thead style=" background-color: #01406d; color:white; ">
                                                <tr>
                                                    <th>Department</th>
                                                </tr>
                                            </thead>
                                            <tbody id="departmentSalesList">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body" style="background-color:#f4f5f5; border-radius:5px">
                        <h5><strong>Stock Summary</strong></h5>
                        <div class="chart-container">
                            <canvas id="donutChart" style="max-height: 150px; overflow-y: auto;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Stacked Bar Chart --}}
        <div class="row">
            <div class="col-md-8">
                <div class="card  border-0" style="width: 100%; background-color:#f4f5f5">
                    <label class="p-3">Receiving Summary</label>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="stackedBarChart"
                                style="min-height: 200px; height: 550px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="container p-3" style="background-color: #01406d; border-radius: 5px">
                    <h5 style="color:white;"> Stock Update</h5>
                    <ul class="list-group" style="max-height: 242px; overflow-y: auto;" id="list-group">
                        <!-- Add more items as needed -->
                    </ul>
                </div>

            </div>
        </div>

        <div class="row ">
            <div class="col-10">
                <h2 class="text-black mb-2 p-1" style="font-family: Segoe UI', Tahoma, Geneva, Verdana, sans-serif">Orders
                </h2>
            </div>
            <div class="col-2">
                <div class="text-right mt-1">
                    <form action="/finance/stock_card/print" method="get" target="_blank">
                        <button class="btn btn-info m-2" id="downloadBtn">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="ordersTable" class="table table-striped table-bordered table-info table-sm" style="width:100%; ">
                <thead style=" background-color: #01406d; color:white; ">
                    <tr>
                        <th width="5%"> </th>
                        <th width="20%" class="text-center">Item</th>
                        <th width="5%"> Stock In</th>
                        <th width="5%">Stock Out</th>
                        <th width="5%">Initial Onhand</th>
                        <th width="5%">Onhand</th>
                        <th width="20%">Department</th>
                        <th width="20%" class="text-center">Remarks</th>
                        <th width="20%" class="text-center">Transacted by</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

@endsection


@section('footerjavascript')

    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>


    <script>

        function getcardNumbers() {
            return $.ajax({
                    type: 'GET',
                    url: '/inventory/dashboard/getcardNumbers',
                })
        }

        function getStocks() {
            return $.ajax({
                    type: 'GET',
                    url: '/inventory/dashboard/getStocks',
                })
        }
        function getStockcard() {
            return $.ajax({
                type: 'GET',
                url: '/finance/inventory/getStockCard'
            })
        }

        function getItemPerDepartment() {
            return $.ajax({
                type: 'GET',
                url: '/inventory/dashboard/getDepartment'
            })
        }

        function getStockSummary() {
            return $.ajax({
                type: 'GET',
                url: '/inventory/dashboard/getStockSummary'
            })
        }

        function getReceivingSummary() {
            return $.ajax({
                type: 'GET',
                url: '/inventory/dashboard/getReceivingSummary'
            })
        }


        function renderAllTotal(){

            getcardNumbers().done(function (data) {
                $('#totalPurchase').text('₱' + data.totalPurchase); // Adding ₱ symbol before total
                $('#totalitem').text(data.totalItem); // Adding ₱ symbol before total
                $('#totalReceive').text(data.totalReceive); // Adding ₱ symbol before total
                $('#totalReceiveMoney').text('₱' + data.totalReceiveMoney); // Adding ₱ symbol before total
            });


        }

        function renderStocks(){

            getStocks().done(function (data) {
                renderHtml = data.length > 0 ? data.map(entry => {
                    return `<li class="list-group-item d-flex justify-content-between align-items-center">
                                ${entry.description}
                                <span class="${entry.badge} rounded-pill">${entry.qty}</span>
                            </li>`;
                    }).join(''):
                    `<li class="list-group-item d-flex justify-content-between align-items-center">
                                No Item
                    </li>`;
            
            $('#list-group').html(renderHtml);

            });



        }

        function renderDepartment(){

            getItemPerDepartment().done(function (data) {

                renderHtml2 = data.length > 0 ? data.map(entry => {
                    return `<tr> <td> ${entry.department}</td> </tr>`;
                    }).join(''):
                    `<tr> <td> No Department Available</td> </tr>`;
            
            $('#departmentSalesList').html(renderHtml2);

            });



        }

        function renderSummary(){

            getStockSummary().done(function (array) {
                  // Sample data for the donut chart
                var data = {
                    labels: ['LOW STOCK', ''],
                    datasets: [{
                        data: array,
                        backgroundColor: ['#ff7a0f', '#01b4ba']
                    }]
                };

                var ctx = document.getElementById('donutChart').getContext('2d');

                var donutChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: data
                });


            });



        }

        function renderStockcard() {


            getStockcard().done(function (data) {

        
                var table = $('#ordersTable').DataTable({
                    pageLength: 5,
                    lengthMenu: [5, 10, 15, 20],
                    autoWidth: true,
                    responsive: true,
                    destroy: true,
                    stateSave: true,
                    data: data,
                    columns: [
                        { data: null, render: function(data, type, row, meta) { return meta.row + 1; } }, // Index column
                        { data: 'description', className: 'text-center'},
                        { data: 'stock_in' , className: 'text-center' },
                        { data: 'stock_out' , className: 'text-center' },
                        { data: 'initial_onhand' , className: 'text-center' },
                        { data: 'onhand' , className: 'text-center' },
                        { data: 'deparment_name' },
                        { data: 'remarks'},
                        { data: 'name', className: 'text-center' },
                    ],
                });

            });

        }

        function renderReceivingSummary(){

            getReceivingSummary().done(function (array) {
                
                console.log(array);
                var data = {
                    labels: array.label,
                    datasets: [{
                            label: 'Item Qty',
                            backgroundColor: '#01406d', // Hexadecimal color for orange
                            data: array.data
                        },
                    ]
                };
                var options = {
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                };
                var ctx = document.getElementById('stackedBarChart').getContext('2d');
        
                var stackedBarChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options
                });

            });

        }




        $(document).ready(function() {
            $('#ordersTable').DataTable();
            renderAllTotal();
            renderStocks();
            renderStockcard();
            renderDepartment();
            renderSummary();
            renderReceivingSummary();
        });
    </script>

@endsection

