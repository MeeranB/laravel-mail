@if($template == 'BookingUserComplete' || $template == 'BookingUserCancelled')
    <tr>
        <td class='row_container row_container--standard pt-3'>
            <p class="text text_style-full_width">Dear {{ $booking->getSubmitterFullName() }},</p>
            <p class="text text_style-full_width">Your Booking at {{ $booking->property_name }} is now confirmed.</p>
        </td>
    </tr>
@elseif($template == 'BookingPropertyCancelled')
    <tr>
        <td class='row_container row_container--standard pt-3'>
            <p class="text text_style-full_width">Dear team at {{ $booking->property_name }},</p>
            <p class="text text_style-full_width">The following booking has been cancelled</p>
        </td>
    </tr>
@elseif($template == 'BookingPaymentComplete')
    <tr>
        <td class='row_container row_container--standard pt-3'>
            <p class="text text_style-full_width">Dear {{ $booking->getSubmitterFullName() }},</p>
            <p class="text text_style-full_width">We've received the final payment on your booking. Please enjoy your stay!</p>
        </td>
    </tr>
@elseif($template == 'BookingPaymentFailed')
    <tr>
        <td class='row_container row_container--standard pt-3'>
            <p class="text text_style-full_width">A failed payment attempt has been made agaist the following booking detailed below -</p>
        </td>
    </tr>
@elseif ($template == 'PasswordUserReset' || $template == 'PasswordUserSet')
    <tr>
        <td class='row_container row_container--standard pt-3'>
            <h2><span class="text text_style--medium text_style-full_width">Hello {{ $user->getNameOrEmail() }},</span></h2>
            <p class="text text_style-full_width">Please use the url provided below to {{ $template == 'PasswordUserReset' ? 'reset' : 'set' }} your account password:</p>
        </td>
    </tr>
@elseif ($template == 'UserVerifyAccount')
    <tr>
        <td class='row_container row_container--standard pt-3'>
            <h2><span class="text text_style--medium text_style-full_width">Hello {{ $user->getNameOrEmail() }},</span></h2>
            <p class="text text_style-full_width">Please use the 'Activate account' button below to activate your Chic Retreats account:</p>
        </td>
    </tr>
@elseif ($template == 'UserQueryConfirm')
    <tr>
        <td class='row_container row_container--standard'>
            <h2><span class="text text_style-full_width">Dear {{ $userQuery->getName() }},</span></h2>
            <p class="text text_style-full_width">Thank you for considering Chic Retreats, home of the discerning traveller. Our expert travel team will be in contact as soon as possible to help you with your enquiry.</p>
        </td>
    </tr>
@endif
