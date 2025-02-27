@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
            }
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
    </style>
   

@endsection


@section('content')
@php
    $studentinfo = DB::table('studinfo')
                ->where('sid',str_replace('S','',auth()->user()->email))
                ->select(
                    'contactno',
                    'fcontactno',
                    'gcontactno',
                    'mcontactno',
                    'isguardannum',
                    'isfathernum',
                    'ismothernum'
                )
                ->first();
@endphp

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Payment Upload</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Payment Upload</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-filter"></i></span>
                  <div class="info-box-content">
                      <div class="row">
                          <div class="col-md-12">
                              <label class="info-box-text">Enrollment</label>
                                <span class="info-box-number">
                                    <select class="form-control form-control-sm select2" id="filter_sy" ></select>
                                </span>
                          </div>
                           <div class="col-md-6" hidden>
                              <span class="info-box-text">Semester</span>
                                <span class="info-box-number">
                                    <select class="form-control" id="filter_sem" >
                                        @php
                                            $sy = DB::table('semester')->get();
                                        @endphp
                                        @foreach ($sy as $item)
                                            @php
                                                $selected = '';
                                                if($item->isactive == 1){
                                                    $selected = 'selected="selected"';
                                                }
                                            @endphp
                                            <option value="{{$item->id}}" {{$selected}} value="{{$item->id}}">{{$item->semester}}</option>
                                    @endforeach
                                    </select>
                                </span>
                          </div>
                      </div>
                    
                  </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-2" hidden>
              <div class="info-box">
                <div class="info-box-content">
                  <span class="info-box-text">Total Annual Fee</span>
                  <span class="info-box-number" id="tuition">
                  </span>
                </div>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box mb-3">
                <div class="info-box-content">
                  <span class="info-box-text">Balance</span>
                  <span class="info-box-number" id="balance"></span>
                </div>
              </div>
            </div>
            <div class="clearfix hidden-md-up"></div>
            {{-- <div class="col-12 col-sm-6 col-md-2">
              <div class="info-box mb-3">
                <div class="info-box-content">
                  <span class="info-box-text" >Amount Paid</span>
                  <span class="info-box-number" id="paid"></span>
                </div>
              </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow" id="card_nopayment" hidden>
                    <div class="card-header bg-danger">
                        <em> <i class="fas fa-exclamation-circle mr-1"></i> No Payment Assigned Yet!</em>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <form action="/payment/online/submitreceipt" id="paymentInfo" method="POST" enctype="multipart/form-data">
                                    @csrf
                                        {{-- <input name="syid" id="input_syid" hidden>
                                        <input name="semid" id="input_semid" hidden> --}}
                                        <div class=" row " >
                                            <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                                <h6 for="">PAYMENT TYPE</h6>
                                                                <select name="paymentType" id="paymentType" class="form-control ">
                                                                    <option value="">SELECT PAYMENT TYPE</option>
                                                                    @foreach(DB::table('paymenttype')->where('isonline','1')->where('deleted','0')->get() as $item)
                                                                            <option value="{{$item->id}}">{{$item->description}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>Payment type is required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                                <h6 for="">RECEIPT IMAGE (<em>Only accepts image file types, such as JPG, JPEG, or PNG.</em> ).</h6>
                                                                <input type="file" class="form-control" name="recieptImage" id="recieptImage" accept=".png, .jpg, .jpeg">
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>Reciept Image is empty</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                                <h6 for="">REFERENCE NUMBER </h6>
                                                                <input class="form-control" name="refNum" id="refNum" placeholder="REFERENCE NUMBER">
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong id="refNumError">Reference Number is required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                                <h6 for="">BANK NAME</h6>
                                                                <select id="bankName" name="bankName" class="form-control" disabled>
                                                                    <option value="">SELECT BANK</option>
                                                                    @foreach (DB::table('onlinepaymentoptions')->where('paymenttype','3')->where('deleted','0')->where('isActive','1')->get() as $item)
                                                                            <option value="{{$item->optionDescription}}">{{$item->optionDescription}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>Bank name is required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                                <label for="">BANK TRANS. DATE</label>
                                                                <input type="date"  class="form-control" name="transDate" id="transDate" >
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong>Bank Transaction Date is required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                                <h5 for="">PAYMENT AMOUNT</h5>
                                                                <input class="form-control" type="text" name="amount" id="amount"  value="" data-type="currency" placeholder="00.00">
    
                                                                <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                    <strong id="amountError">Amount is required</strong>
                                                                </span>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <h5 for="">Message Reciever:</h5>
                                                                <select name="" id="input_receiver" class="form-control">
                                                                    <option value="1">Student</option>
                                                                    <option value="2">Mother</option>
                                                                    <option value="3">Father</option>
                                                                    <option value="4">Guardian</option>
                                                                </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <h5 for="">Contact #:</h5>
                                                            <input placeholder="09XX-XXXX-XXXX" id="input_number" name="input_number" class="form-control">
                                                            <span class="invalid-feedback" role="alert" style="display:hidden">
                                                                <strong id="opcontact">Contact is required/invalid</strong>
                                                            </span>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <button class="btn btn-success" id="proceedpayment" >
                                                                UPLOAD PAYMENT RECEIPT
                                                            </button>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="scheduler-border">
                                                        <legend class="scheduler-border">Receipt Image</legend>
                                                        <img class="mt-3 w-100" id="receipt"  />
                                                </fieldset>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</section>


<script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function(){


        function load_balance(){
            
        }


        $(document).on('change','#paymentType',function (){
            if($(this).val() == 3){
                    $('#bankName').removeAttr('disabled')
            }else{
                    $('#bankName').attr('disabled','disabled')
                    $('#bankName').val("")
            }
        })


        $("#input_number").inputmask({mask: "9999-999-9999"});

        var studinfo = @json($studentinfo);
      
        $('#input_number').val(studinfo.contactno)
        $(document).on('change','#input_receiver',function(){
            if($(this).val() == 1){
                $('#input_number').val(studinfo.contactno)
            }else if($(this).val() == 3){
                $('#input_number').val(studinfo.fcontactno)
            }else if($(this).val() == 2){
                $('#input_number').val(studinfo.mcontactno)
            }else if($(this).val() == 4){
                $('#input_number').val(studinfo.gcontactno)
            }
        })

        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
        
        $( '#paymentInfo' )
                  .submit( function( e ) {

                    if($('#filter_sy').val() != null && $('#filter_sy').val() != ''){
                        var val_info = $('#filter_sy').val().split('-');
                    }else{
                        return false;
                    }
                    
                    var temp_sy = val_info[0]
                    var temp_sem = val_info[1]

                    var valid_input = true

                    $('#amount').removeClass('is-invalid')
                    $('#refNum').removeClass('is-invalid')
                    $('#paymentType').removeClass('is-invalid')
                    $('#recieptImage').removeClass('is-invalid')
                    $('#transDate').removeClass('is-invalid')
                    $('#bankName').removeClass('is-invalid')
                    $('#refNumError').text('Reference Number is required')
                    $('#input_number').removeClass('is-invalid')

                    if($('#paymentType').val() == ""){
                        $('#paymentType').addClass('is-invalid')
                        valid_input = false
                        return false
                    }

                    if($('#input_number').val() == "" || ($('#input_number').val()).toString().replace(/-|_/g,'').length != 11){
                        $('#input_number').addClass('is-invalid')
                        valid_input = false
                        return false
                    }

                    if($('#recieptImage').val() == ""){
                        $('#recieptImage').addClass('is-invalid')
                        valid_input = false
                        return false
                    }

                    if($('#refNum').val() == ""){
                        $('#refNum').addClass('is-invalid')
                        valid_input = false
                        return false
                    }

                    if($('#paymentType').val() == 3){
                        if($('#bankName').val() == ""){
                            $('#bankName').addClass('is-invalid')
                            valid_input = false
                            return false
                        }
                    }
                    
                    if($('#transDate').val() == ""){
                        $('#transDate').addClass('is-invalid')
                        valid_input = false
                        return false
                    }
                   
                    if($('#amount').val() == 0 || $('#amount').val() == 0.00 || $('#amount').val() == ""){
                        $('#amount').addClass('is-invalid')
                        valid_input = false
                        return false
                    }

                    $('#input_syid').val()

                    var inputs = new FormData(this)

                    inputs.append('syid', temp_sy)
                    inputs.append('semid', temp_sem)

                    Swal.fire({
                              title: 'Do you want to upload payment receipt?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'UPLOAD PAYMENT RECEIPT'
                    }).then((result) => {
                        if (result.value) {
                            if(valid_input){

                                $( '#proceedpayment' ).text('UPLOADING..')
                                $( '#proceedpayment' ).removeClass('btn-success')
                                $( '#proceedpayment' ).addClass('btn-secondary')
                                $( '#proceedpayment' ).attr('disabled','disabled')

                                $.ajax( {
                                    url: '/payment/upload',
                                    type: 'POST',
                                    data: inputs,
                                    processData: false,
                                    contentType: false,
                                    success:function(data) {
                                        $( '#proceedpayment' ).text('UPLOAD PAYMENT RECEIPT')
                                        $( '#proceedpayment' ).removeClass('btn-secondary')
                                        $( '#proceedpayment' ).addClass('btn-success')
                                        $( '#proceedpayment' ).removeAttr('disabled')

                                        if(data[0].status == 0){
                                            $('#refNum').addClass('is-invalid')
                                            $('#refNumError').text(data[0].message)
                                            Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                            })
                                        }else if(data[0].status == 1){
                                            Toast.fire({
                                                type: 'success',
                                                title: 'Uploaded successfully'
                                            })

                                            $('#amount').val("")
                                            $('#refNum').val("")
                                            $('#paymentType').val("")
                                            $('#recieptImage').val("")
                                            $('#transDate').val("")
                                            $('#bankName').val("")
                                            $('#receipt').attr("src","")

                                        }
                                    },error:function(){
                                        $( '#proceedpayment' ).text('UPLOAD PAYMENT RECEIPT')
                                        $( '#proceedpayment' ).removeClass('btn-secondary')
                                        $( '#proceedpayment' ).addClass('btn-success')
                                        $( '#proceedpayment' ).removeAttr('disabled')

                                        Toast.fire({
                                            type: 'error',
                                            title: 'Something went wrong!'
                                        })

                                    }
                                });
                            }
                        }
                    })

                    

                    

                    e.preventDefault();
            });
        
    })
</script>

<script>
       

        $(document).ready(function(){

            get_enrollment_history()
			var all_enrollment = []

            function get_enrollment_history(){
                $.ajax({
                    type:'GET',
                    url: '/payment/balanceinfo',
                    success:function(data) {
						
						var active_sy = null
						$.each(data,function(a,b){
							if(b.isactive == 1){
								active_sy = b
							}
						})
                        $('#filter_sy').empty()
                        $('#filter_sy').select2({
                            data:data,
                            placeholder:'Select Enrollment'    
                        })
						console.log(data);
                        if (active_sy) {
                            $('#filter_sy').val(active_sy.id).change()
                        }
						all_enrollment = data
                        get_ledger()
                    }

                })
            }


            $(document).on('change','#filter_sy',function(){
                get_ledger()
            })

            function get_ledger(){
                $('#balance').empty();
                $('#paid').empty();
                $('#student_ledger').empty();

                if($('#filter_sy').val() != null && $('#filter_sy').val() != ''){
                    var val_info = $('#filter_sy').val().split('-');
                }else{
                    $('#card_nopayment').attr('hidden', false)
                    $('#paymentInfo').find('input, button, select').attr('disabled', true)
                    $('#paymentInfo').attr('hidden',true)
                    $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
                    return false;
                }
                
				
				
                var temp_sy = val_info[0]
                var temp_sem = val_info[1]
				
				var templevelid = all_enrollment.filter(x=>x.syid == temp_sy && x.semid == temp_sem)
				
				if(templevelid.length == 0){
                    $('#card_nopayment').attr('hidden', false)
                    $('#paymentInfo').find('input, button, select').attr('disabled', true)
                    $('#paymentInfo').attr('hidden',true)
                    $('#balance')[0].innerHTML = '&#8369; ' + '0.00';
					return false;
				}
				
				
                $.ajax({
                    type:'GET',
                    url: '/ledger',
                    data:{
                        syid:temp_sy,
                        semid:temp_sem,
						levelid:templevelid[0].levelid
                    },
                    success:function(data) {
                        console.log('laksjdlkjsdf',data);

                        if(data.length  > 0 ){

                            var total_amount = 0;
                            var total_payment = 0;
                            var total_balance = 0
                            var abalance = parseFloat(0).toFixed(2)
                        
                            var total_payment = parseFloat(0).toFixed(2)

                            var tolal_charges_ledger = parseFloat(0).toFixed(2)
                            var tolal_payment_ledger = parseFloat(0).toFixed(2)
                            var runbal = 0;

                            $.each(data,function(a,b){

                                    var ornum = ''
                                    if(b.ornum != ''){
                                        ornum = b.ornum
                                    }
                                    runbal += parseFloat( b.amount )
                                    runbal -=  parseFloat(b.payment)

                                    if(b.amount > 0){
                                        aamount = '&#8369;' + parseFloat(b.amount).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                    }else{
                                        aamount = ''
                                    }

                                    if(b.payment > 0){
                                        apayment = '&#8369;' + parseFloat(b.payment).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                    }else{
                                        apayment = ''
                                    }

                                    if(b.ornum != null){
                                        total_payment = parseFloat(total_payment) + parseFloat(b.payment)
                                    }

                                    tolal_charges_ledger = parseFloat(tolal_charges_ledger) + parseFloat(b.amount)
                                    tolal_payment_ledger = parseFloat(tolal_payment_ledger) + parseFloat(b.payment)

                                 console.log(runbal)

                            })
                            

                            if(data.length != 0 || runbal != 0){
                                runbal = parseFloat(runbal).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                $('#card_nopayment').attr('hidden', true)
                                $('#paymentInfo').find('input, button, select').attr('disabled', false)
                                $('#paymentInfo').attr('hidden',false)
                            }else{
                                runbal = parseFloat(0).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                $('#card_nopayment').attr('hidden', false)
                                $('#paymentInfo').find('input, button, select').attr('disabled', true)
                                $('#paymentInfo').attr('hidden',true)
                            }
							
							

                            $('#balance')[0].innerHTML = '&#8369; '+runbal
                            $('#paid')[0].innerHTML = '&#8369; '+parseFloat(total_payment).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")

                        }else{

                            $('#balance')[0].innerHTML = '&#8369; 0.00'
                            $('#paid')[0].innerHTML = '&#8369; 0.00'
                            runbal = parseFloat(0).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")
                                $('#student_ledger').append('<tr class="bg-info"><td class="text-right">REMAINING BALANCE</td><td class="text-right">&#8369; 0.00</td><td></td></tr>')
                        }
                    }
                })
            }
        })

</script>

<script>
    $(document).ready(function(){

        function readURL(input) {
              if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function (e) {
                    $('#receipt').attr('src', e.target.result);
                    }
                    
                    reader.readAsDataURL(input.files[0]);
              }
        }
        
        $("#recieptImage").change(function(){
                readURL(this);
                var files = this.files;
                console.log(files);
                
                var input = $(this);
                for (var i = 0; i < files.length; i++) {
                    console.log(files[i]);
                    
                    if (!files[i].type.startsWith('image/')) {
                            Swal.fire({
                                type: 'warning',
                                title: 'Invalid file',
                                text: `Only image files are allowed!`,
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            })
                            this.value = ''; // Clear the input
                            input.addClass('is-invalid')
                            break;
                    }else{
                            input.addClass('is-valid')
                            input.removeClass('is-invalid')
                    }
                }
        });


        $("input[data-type='currency']").on({
                keyup: function() {
                    formatCurrency($(this));
                },
                blur: function() { 
                    formatCurrency($(this), "blur");
                }
        });
        

        function formatNumber(n) {
        // format number 1000000 to 1,234,567
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }


        function formatCurrency(input, blur) {
        // appends $ to value, validates decimal side
        // and puts cursor back in right position.
        
        // get input value
        var input_val = input.val();
        
        // don't validate empty input
        if (input_val === "") { return; }
        
        // original length
        var original_len = input_val.length;

        // initial caret position 
        var caret_pos = input.prop("selectionStart");
        
        // check for decimal
        if (input_val.indexOf(".") >= 0) {

        // get position of first decimal
        // this prevents multiple decimals from
        // being entered
        var decimal_pos = input_val.indexOf(".");

        // split number by decimal point
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);

        // add commas to left side of number
        left_side = formatNumber(left_side);

        // validate right side
        right_side = formatNumber(right_side);
        
        // On blur make sure 2 numbers after decimal
        if (blur === "blur") {
                right_side += "00";
        }
        
        // Limit decimal to only 2 digits
        right_side = right_side.substring(0, 2);

        // join number by .
        input_val =  left_side + "." + right_side;

        } else {
        // no decimal entered
        // add commas to number
        // remove all non-digits
        input_val = formatNumber(input_val);
        input_val = input_val;
        
        // final formatting
        if (blur === "blur") {
                input_val += ".00";
        }
        }
        
        // send updated string to input
        input.val(input_val);

        // put caret back in the right position
        var updated_len = input_val.length;
        caret_pos = updated_len - original_len + caret_pos;
        input[0].setSelectionRange(caret_pos, caret_pos);
        }

    })
</script>

@endsection
