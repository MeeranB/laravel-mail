<table role='presentation' class='body'>
    <tr>
        <td align='center'>
            <table role="presentation" class='transparent-container' >
                @if ($theme == 'chicretreats')
                <tr>
                    <td id='footer-container' class='row_container row_container--header space-vertically-large'>
                        <table class='row' role="presentation">
                            <tr>
                                <td class='column column-type--third column-type--mobile text_style--center'>
                                    <table class='row' id='footer' role='presentation'>
                                        <tr>
                                            <td class='column column-type--header_img display-mobile'>
                                                <a href="http://www.facebook.com/pages/Chic-Retreats/120263961334152#!/pages/Chic-Retreats/120263961334152?v=wall"><img class='header-img' src="{{ $images['facebook'] }}"></a>
                                            </td>
                                            <td class='column column-type--header_img display-mobile'>
                                                <a href="https://www.instagram.com/chic.retreats/"><img class='header-img' src="{{ $images['instagram'] }}"></a>
                                            </td>
                                            <td class='column column-type--header_img display-mobile'>
                                                <a href="https://twitter.com/chicretreat"><img class='header-img' src="{{ $images['twitter'] }}"></a>
                                            </td>
                                            <td class='column column-type--header_img display-mobile'>
                                                <a href="https://www.pinterest.com/chicretreats/"><img class='header-img' src="{{ $images['pinterest'] }}"></a>
                                            </td>
                                            <td class='column column-type--header_img display-mobile'>
                                                <a href="https://www.linkedin.com/company/chic-retreats"><img class='header-img' src="{{ $images['linkedin'] }}"></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td class='column column-type--third text_style--end mobile-center mobile-space-vertically'>
                                    <a class='text link vertical-align--middle text_style--header' href='mailto:{{$config->email}}'>
                                    <img class='header-img' src="{{ $images['email'] }}">
                                    Reservations
                                    </a>
                                </td>
                                <td class='column column-type--third text_style--center mobile-center mobile-space-vertically'>
                                    <a class='text link vertical-align--middle text_style--header' href='tel:{{$phone}}'>
                                    <img class='header-img' src="{{ $images['phone'] }}">
                                    {{$phone}}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif
                <tr>
                    <td align='center' @if ($theme == 'staybooked') class='row_container row_container--header space-vertically-large' @endif>
                        <p class='text text_style--center'>Chic Retreats ™ © 2002 - {{ date('Y') }} is a registered trademark of Teygon Ltd. a company registered in the United Kingdom with offices at 120-122 Braymere Road, Hampton Centre, Peterborough PE7 8NB</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
