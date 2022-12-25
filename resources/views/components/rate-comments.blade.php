@if ($rateComments)
<tr>
    <td class='row_container row_container--standard'>
        <p class="text">
            Contract Remarks:
        </p>
        <ul>
            @foreach ($rateComments as $comment)
                @if ( isset($comment->description) && !empty($comment->description))
                    <li class='text'>{{ ucfirst($comment->description) }} - {{ ucfirst($comment->text) }}</li>
                @elseif (isset($comment->text) && empty($comment->description))
                    <li class='text'>{{ ucfirst($comment->text) }}</li>
                @endif
            @endforeach
        </ul>
    </td>
</tr>
@endif