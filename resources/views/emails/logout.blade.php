<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', @$type . ' Daily Logout')
    @section('subject', @$data['store']['name'] . ' - ' . date( 'Y-m-d g:ia' , strtotime( @$data['start'] ) ) . ' to ' . date( 'Y-m-d g:ia' , strtotime( @$data['end'] ) ) )

    @section('header-background',  asset('public/assets/images/email/buyerslogout.png'))
    @section('header-title',  'NEW DAILY LOGOUT')
    @section('header-title-background',  '#f16000')
    @section('header-big-title',  @$data['store']['name'])
    @section('header-small-title', date( 'Y-m-d g:iA' , strtotime( @$data['start'] ) ) . ' TO ' . date( 'Y-m-d g:iA' , strtotime( @$data['end'] ) ) )
    
    @section('content')

        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
            <thead>
                <tr>
                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';"></th>
                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';"></th>
                </tr>
            </thead>
            <tbody>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Author:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['user_creator'] }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <strong style="font-family:'Helvetica';">Recap:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['recap'] }}
                    </td>
                </tr>
                <!--
                @if(!empty(@$data['last_year_sales']))
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Last Years Sales <small>(MTD)</small>:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        @${{ number_format(@$data['last_year_sales'],2) }}
                    </td>
                </tr>
                @endif
                @if(!empty(@$data['this_year_sales']))
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">This Years Sales <small>(MTD)</small>:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        @${{ number_format(@$data['this_year_sales'],2) }}
                    </td>
                </tr>
                @endif
                -->
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Last Year MTD Sales:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        ${{ number_format(@$data['lymtd'],2) }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">MTD Sales:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        ${{ number_format(@$data['mtd'],2) }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Today's Sales:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        ${{ number_format(@$data['sales'],2) }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Returns:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        ${{ number_format(@$data['returns'],2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        <p style="margin:5px 0;"><strong style="font-family:'Helvetica';">Staff Working:</strong>
                        <table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
                            <thead>
                                <tr style="background-color:#F9F9F9;">
                                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">
                                        <strong style="font-family:'Helvetica';">Staff Name</strong>
                                    </th>
                                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">
                                        <strong style="font-family:'Helvetica';">Hours</strong>
                                    </th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr style="background-color:#F9F9F9;">
                                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica'; text-align:right;">
                                        <strong style="font-family:'Helvetica';">Total Hours:</strong>
                                    </th>
                                    <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica'; text-align:left;">
                                        <strong style="font-family:'Helvetica';">{{ @$data['total_hours'] }}</strong>
                                    </th>
                                </tr>
                            <tbody>
                                @if(@$data['staff'])
                                    @foreach($data['staff'] as $index => $staff)
                                    <tr>
                                        <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                            {{ @$staff['firstname'] . ' ' . @$staff['lastname'] }}
                                        </td>
                                        <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                            {{ @$staff['pivot']['hours'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Traffic:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['traffic'] }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Conversions:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ str_replace('.00', '', @$data['conversions'] ) }}%
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Insoles:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['insoles'] }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Notes:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['notes'] }}
                    </td>
                </tr>
            </tbody>
        </table>

	@stop
