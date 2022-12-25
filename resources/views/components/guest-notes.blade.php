@if (count($guestNotes) >= 1)
<tr>
    <td class='row_container row_container--standard'>
        <p class="text">
            <strong>Special Request(s):</strong>
        </p>
        @foreach ($guestNotes as $note)
            <p class="text">{{ htmlspecialchars($note) }}</p>
        @endforeach
    </td>
</tr>
@endif