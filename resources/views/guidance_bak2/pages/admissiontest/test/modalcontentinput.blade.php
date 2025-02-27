@foreach($returninput as $item)

    <div class="form-group">
        <label>{{$item->setup}}</label>
        <div class="input-group">
            <input class="form-control form-control inputfield" type="number" placeholder="Enter a value between 0 and 100" data-id="{{$item->id}}" onkeyup="validateInput(this)">
            <div class="input-group-append">
                <button type="button" class="btn btn-secondary" onclick="markAsNA(this)">N/A</button>
            </div>
        </div>
    </div>

@endforeach

    <div class="row">
            <div class="col-md-12">
            <button  type="button" class="btn btn-primary btn" id="saveInput">Save <i class="fas fa-save"></i>  </button>
            </div>
    </div>


<script>


function validateInput(input) {
    if (input.value < 0) {
        alert('Value cannot be less than 0');
        input.value = '';
    }

    if (input.value > 100) {
        alert('Value cannot be greater than 100');
        input.value = '100';
    }

    if (input.value.length > 2) {
        alert('Value should not exceed 2 characters');
        input.value = input.value.slice(0, 2);
    }
}

function markAsNA(button) {
    // Assuming the input is the previous sibling of the button within the same parent
    $(button).parent().parent().parent().remove();
}

</script>