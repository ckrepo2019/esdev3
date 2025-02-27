<style>
    
    td, th{
      padding: 3px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered" id="table-transactions" style="font-size: 11px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>OR No.</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Cashier</th>
                    <th>Payment Type</th>
                </tr>
            </thead>
            <tbody>
                <tr style="font-size: 15px;">
                    <th></th>
                    <th></th>
                    <th class="text-right">TOTAL</th>
                    <th class="text-right">{{number_format(collect($transactions)->sum('amountpaid'),2)}}</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($transactions as $transaction)
                    <tr>
                        <td class="text-bold">{{$transaction->transdate}}</td>
                        <td class="text-bold">{{$transaction->ornum}}</td>
                        <td>{{$transaction->studname}}</td>
                        <td class="text-right text-bold">{{number_format($transaction->amountpaid,2)}}</td>
                        <td>{{$transaction->transby}}</td>
                        <td class="text-center">{{$transaction->paymenttype}}</td>
                    </tr>
                @endforeach
                <tr style="font-size: 15px;">
                    <th></th>
                    <th></th>
                    <th class="text-right">TOTAL</th>
                    <th class="text-right">{{number_format(collect($transactions)->sum('amountpaid'),2)}}</th>
                    <th></th>
                    <th></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    
    $('#table-transactions').DataTable({
            "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });

</script>