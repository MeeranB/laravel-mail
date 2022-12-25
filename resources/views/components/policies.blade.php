@foreach ($policies as $policy => $data)
    @if($data)
    <tr>
        <td class='row_container row_container--standard space-vertically-small'>
            <p class="text"><strong>{{ $policy }}</strong></p>
            <span class="text">{!! $data !!}</span>
        </td>
    </tr>
    @endif
@endforeach
@if(!in_array($template, ['BookingPaymentComplete', 'BookingPaymentFailed']))
    <tr>
        <td class='row_container row_container--standard'>
            <p class="text">Most countries impose a City / Tourist / Eco Tax any such charges are payable directly to the property on arrival.</p>
        </td>
    </tr>
@endif