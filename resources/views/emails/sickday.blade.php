<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'Sick Day')
    @section('subject', date( 'l, M d, Y' , strtotime( @$data->date ) ) )

    @section('header-background',  asset('public/assets/images/email/sickday.png'))
    @section('header-title',  'Sick Day')
    @section('header-title-background',  '#a80019')
    @section('header-big-title',  '')
    @section('header-small-title', '' )

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
                        <strong style="font-family:'Helvetica';">Date:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data->date }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">User:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data->user->firstname }} {{ @$data->user->lastname }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Store:</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data->store->name }}
                    </td>
                </tr>
                <tr style="background-color:#F9F9F9;">
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                        <strong style="font-family:'Helvetica';">Details</strong>
                    </td>
                    <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                        {{ @$data->details }}
                    </td>
                </tr>
            </tbody>
        </table>

	@stop
