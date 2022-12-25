@foreach ($statements as $statement)
    @if($getPricingStatements()->$statement->isVisible)
        <tr>
            <td class="row_container row_container--standard @if($loop->first) space-vertically @endif">
                <p class='text'>{!! $getPricingStatements()->$statement->value !!}</p>
            </td>
        </tr>
    @endif
@endforeach