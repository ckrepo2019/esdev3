@if(count($messages)==0)
<div class="uk-grid-margin">
    <div class="uk-padding uk-panel ">
        <p class="uk-h4 text-center">
            {{-- <div class="row text-center"> --}}
                    <img src="{{asset('assets/images/handwave.png')}}"  alt="" style="width:40px">
                    Be the first to say "Hi!"
            {{-- </div> --}}
        </p>
    </div>
</div>
@else
    @foreach($messages as $message)
        @if($message->mine == 1)
            <div class="message-bubble me">
                <div class="message-bubble-inner">
                    <div class="message-avatar">
                        <img src="{{asset($message->picurl)}}" onerror="this.onerror = null, this.src='{{asset($message->avatar)}}'" alt="">
                    </div>
                    <div class="message-text">
                        <input type="hidden" name="messageid" value="{{$message->id}}"/>
                        <p>{{$message->content}}</p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        @elseif($message->mine == 0)
            <div class="message-bubble">
                <div class="message-bubble-inner">
                    <div class="message-avatar">
                        <img src="{{asset($message->picurl)}}" onerror="this.onerror = null, this.src='{{asset($message->avatar)}}'" alt="">
                    </div>
                    <div class="message-text">
                        <input type="hidden" name="messageid" value="{{$message->id}}"/>
                        <p>{{$message->content}}</p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        @endif
    @endforeach
@endif