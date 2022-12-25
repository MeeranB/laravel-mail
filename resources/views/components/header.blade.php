<tr>
    <td align='center'>
        <p class='text text_style--center'>Add <a class='text link' href="mailto:{{$email}}">{{ $email}}</a> to your address book to ensure delivery</p>
    </td>
</tr>
<tr>
    <td class='row_container row_container--header space-vertically-small'>
        <table class='row' role="presentation">
            <tr>
                <td class='column column-type--logo mobile-center'>
                    <img class='logo' width="234px" src="{{ $logo }}">
                </td>
                <td class='column column-type--third text_style--end mobile-center'>
                    <a class='text link vertical-align--middle text_style--header' href='tel:{{$phone}}'>
                    <img class='header-img' src="{{ $phoneImg }}">
                    {{$phone}}
                    </a>
                </td>
                <td class='column column-type--third text_style--center'>
                    <a class='text link vertical-align--middle text_style--header' href='mailto:{{$email}}'>
                    <img class='header-img' src="{{ $emailImg }}">
                    Reservations
                    </a>
                </td>
            </tr>
        </table>
    </td>
</tr>