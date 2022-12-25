@if ($isChicTreats)
<tr>
    <td class='row_container row_container--standard'>
        <p class="text">
            <b>Chic Treats</b>
        </p>
        <ul>
            @foreach ($isChicTreats as $chicTreat)
                <li>
                    <p class="text">{!! $chicTreat !!}</p>
                </li>
            @endforeach
        </ul>
    </td>
</tr>
@endif