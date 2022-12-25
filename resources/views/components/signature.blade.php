<tr>
    <td class='row_container row_container--standard space-vertically-small'>
        @if($template == 'BookingUserCancelled')
            <p class="text">We hope to see you again soon.</p>
            <br><br>
        @endif

        <p class="text">Kind regards,</p>
        <br>
        <p class="text"><em>{{ $config->regards }}<em></p>
    </td>
</tr>