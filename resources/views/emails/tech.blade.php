<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', @$type . ' Tech Talk Product')
    @section('subject', @$data['name'] )

    @section('header-background',  asset('public/assets/images/email/techtalk.png'))
    @section('header-title',  'Tech Talk')
    @section('header-title-background',  '#377c09')
    @section('header-big-title',  @$type . ' Tech Talk Product' )
    @section('header-small-title', @$data['name'] )

    @section('content')

        <img src="{{ @$data['attachment'] }}" />

        <!-- -->
        <div class="notes" style="padding:10px 0 50px; font-size:15px;">
            {{ @$data['notes'] }}
        </div>

        <!-- Specifications -->
        <table style="width:100%;">
            <tfoot>
                <tr style="border-top:1px solid #DDD;">
                    <td colspan="2" style="border-top:1px solid #DDD;"></td>
                </tr>
            </tfoot>
            <tbody>
                @if(@$data['specifications'])
                    @foreach( $data['specifications'] as $specification )
                        <tr>
                            <td style="padding:10px 3px; border-top:1px solid #DDD; width:50%; text-align:right; font-size:15px;"><strong>{{ @$specification['key'] }}:</strong></td>
                            <td style="padding:10px 3px; border-top:1px solid #DDD; width:50%; text-align:left; font-size:15px;">{{ @$specification['value'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>



	@stop
