<tr>
    <td>
        <a href="{{ $propertyUrl }}">
            <img src="{{ $propertyBanner }}"  style="max-width: 700px; width: 100%;"/>
        </a>
    </td>
</tr>

<tr>
    <td class='row_container row_container--standard'>
        <a style='text-decoration:none' href="{{ $propertyUrl }}">
            <p class="text text_style--property_title">{{$booking->property_name}}</p>
        </a>
    </td>
</tr>
<tr>
    <td class='row_container row_container--standard'>
        <table class='table-reset'>
            <tr>
                <td class='column column-align--top column-type--half_mobile'>
                    <table class='table-reset' id='address'>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->name_1}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->name_2}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->address_1}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->address_2}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->city}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->postcode}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->county}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>{{$booking->property->addresses[0]->country->name}}</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class='column column-align--top column-type--half_mobile'>
                    <table class='table-reset' id='contact'>
                        <tr>
                            <td>
                                <p class='text'>Phone: <a class="text link" href="tel:{{$booking->property->getPropertyPhone()}}">{{$booking->property->getPropertyPhone()}}</a></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>E-mail: <a class="text link" href="mailto:{{$booking->property->getPropertyEmail()}}">{{$booking->property->getPropertyEmail()}}</a></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class='text'>Website: <a class="text link" href="{{$booking->property->website}}">{{$booking->property->website}}<a></p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>