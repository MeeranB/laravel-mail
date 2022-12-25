@if( isset($title) )
    <tr>
        <td class='row_container row_container--standard space-vertically-medium-top'>
            <p class="text text_style-full_width"><b>{{ $title }}</b></p>
        </td>
    </tr>
@endif

<tr>
    <td class='row_container row_container--standard space-vertically'>
        <table class='table-reset internal-table @if( $table['outline'] ) table-outline @endif'>
            <tr>
                <td class='column column-align--top column-type--half_mobile'>
                    <table class='table-reset'>
                        @foreach ($table['left'] as $item => $value)
                            @if($value !== false)
                                <tr>
                                    <td @if ( !$table['right'] ) class='space-vertically-small-bottom' @endif>
                                        <p class="text"><b>{{$item}}</b></p>
                                    </td>
                                    <td @if ( !$table['right'] ) class='space-vertically-small-bottom' style='width:75%' @endif>
                                        <p class="text">{{$value}}</p>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </td>
                @if ( $table['right'] )
                <td class='column column-align--top column-type--half_mobile'>
                    <table class='table-reset'>
                        @foreach ($table['right'] as $item => $value)
                            @if($value !== false)
                                <tr>
                                    <td>
                                        <p class="text"><b>{{$item}}</b></p>
                                    </td>
                                    <td>
                                        <p class="text">{{$value}}</p>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </td>
                @endif
            </tr>
        </table>
    </td>
</tr>

