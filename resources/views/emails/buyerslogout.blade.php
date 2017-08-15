<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', @$type . ' Buyer Logout')
    @section('subject', @$data['store']['name'] . ' - ' . date( 'Y-m-d g:ia' , strtotime( @$data['start'] ) ) . ' to ' . date( 'Y-m-d g:ia' , strtotime( @$data['end'] ) ) )

    @section('header-background',  asset('public/assets/images/email/buyerslogout.png'))
    @section('header-title',  'BUYERS LOGOUT')
    @section('header-title-background',  '#f16000')
    @section('header-big-title',  @$data['store']['name'] )
    @section('header-small-title',  date( 'Y-m-d g:iA' , strtotime( @$data['start'] ) ) . ' TO ' . date( 'Y-m-d g:iA' , strtotime( @$data['end'] ) ))
    
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
                        <strong style="font-family:'Helvetica';">Submitted By:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['name'] }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Location Tomorrow:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data['location'] }}
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
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Last Years Sales <small>(MTD)</small>:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        ${{ number_format(@$data['lymtd'],2) }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">This Years Sales <small>(MTD)</small>:</strong>
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
            </tbody>
        </table>

	@stop
