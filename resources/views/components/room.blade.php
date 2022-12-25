<tr>
    <td class='row_container row_container--standard'>
        <table class='table-reset'>
            <tr>
                <td class='column-align--top column-type--half_mobile display-block--mobile'>
                    @if(count($booking->roomDetails) > 1)
                        <h3>Rooms:</h3>
                    @else
                        <h3>Room:</h3>
                    @endif
                </td>
            </tr>
            <tr>
                @if($room->propertyRoom && count($room->propertyRoom->images) > 0)
                    <td class='column-align--top column-type--half_mobile space-horizontally-medium-right display-block--mobile'>
                        <a href="{{ $propertyUrl }}">
                            <img src="{{ $room->propertyRoom->images[0]->url }}" alt={{ $room->name }} style="width: 100%;"/>
                        </a>
                    </td>
                @else
                    <td class='column-align--top column-type--half_mobile space-horizontally-medium-right display-block--mobile'>
                        <a href="{{ $propertyUrl }}">
                            <img src="https://chicretreats.com/assets/img/property-polaroid-default-chic.png" style="width: 100%;"/>
                        </a>
                    </td>
                @endif
                <td class='column-align--top column-type--half_mobile display-block--mobile'>
                    <table class='table-reset'>
                        <tr>
                            <td class='column column-align--top column-type--half_mobile'>
                                <p class='text'>
                                    <b>Room Type:</b>
                                    {{ $room->name }}
                                </p>
                            </td>
                        </tr>
                        @if($room->board_basis)
                            <tr>
                                <td class='column column-align--top column-type--half_mobile'>
                                    <p class='text'>
                                        <b>Board Basis:</b>
                                        {{ $room->board_basis }}
                                    </p>
                                </td>
                            </tr>
                        @endif
                        @if($room->textAsset)
                            <tr>
                                <td class='column column-align--top column-type--half_mobile'>
                                    <p class='text'>
                                        <b>Description:</b>
                                        {!! $room->textAsset !!}
                                        {{-- how can I inject styling into the returned html (if it is html) --}}
                                    </p>
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
        <br>
    </td>
</tr>


@if (($booking->getSupplier() !== 'consortia' || $template !== 'BookingPropertyComplete') && ($cancellationInterval && $cancellationInterval <= 299))
    <tr>
        <td class='row_container row_container--standard'>
            <h3>Policies:</h3>
            <strong>Cancellation:</strong>
            @foreach($cancellationPolicies as $policy)
                @if(isset($policy->amount) && isset($policy->from))
                    <p class="text">If you cancel on or after {{ date_format($policy->from, 'd/m/Y') }}, you will be charged {{ $booking->currency->currency_symbol }}{{ number_format($policy->amount * $booking->exchange_rate, 2, '.', '') }}.</div>
                    @if($policy->from->format('Y-m-d') <= date('Y-m-d'))
                        <p class='text'>Full Payment: 100% of total required at the time of booking.</p>
                    @endif
                @else
                    <p class="text">{{$policy}}</p>
                @endif
            @endforeach
            @if ( $booking->property->cmg_channel !== 'ACOMOS')
                <p class="text">Note: As detailed in the booking process all deposits paid are Non-refundable</p>
            @endif
        </td>
    </tr>
@endif
