<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'Payroll')
    @section('subject', date( 'l, M d, Y' , strtotime( $data['start'] ) ) . ' to ' . date( 'l, M d, Y' , strtotime( $data['end'] ) ) )
    @section('content')



        @foreach( $data['users'] as $user )

        	<table style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%; border:1px solid #DDD;">
                <thead>
                    <tr style="background-color:#F9F9F9;">
                        <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">{{ $user['firstname'] . ' ' . $user['lastname'] }}</th>
                        <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">Rate</th>
                        <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">Hours</th>
                        <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">Overtime</th>
                        <th style="border-collapse:collapse; border:1px solid #DDD; border-bottom-width:2px; padding:8px; font-family:'Helvetica';">Total</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach( $data['hours'][ $user['id'] ] as $index => $item )
                		<tr>
                            <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">                                                
                                {{ date('l, M d, Y', strtotime( $item['date'] ) ) }}
                            </td>
                            <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                ${{ number_format( (float)$item['rate'] , 2 ) }}
                            </td>
                            <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                               	{{ number_format( (float)$item['hours'] , 2 ) }}
                            </td>
                            <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                {{ number_format( (float)$item['overtime'] , 2 ) }}
                            </td>
                            <td style="border-collapse:collapse; border:1px solid #DDD; padding:8px; font-family:'Helvetica';">
                                ${{ number_format( (float)$item['value'] , 2 ) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="border-collapse:collapse; border:1px solid #DDD; border-top-width:2px; padding:8px; font-family:'Helvetica';">
                            Total:
                        </td>
                        <td style="border-collapse:collapse; border:1px solid #DDD; border-top-width:2px; padding:8px; font-family:'Helvetica';">
                            {{ number_format( (float)$user['total']['hours'] , 2 ) }}
                        </td>
                        <td style="border-collapse:collapse; border:1px solid #DDD; border-top-width:2px; padding:8px; font-family:'Helvetica';">
                            {{ number_format( (float)$user['total']['overtime'] , 2 )}}
                        </td>
                        <td style="border-collapse:collapse; border:1px solid #DDD; border-top-width:2px; padding:8px; font-family:'Helvetica';">
                            ${{ number_format( (float)$user['total']['value'] , 2 ) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <hr style="background:#EFEFEF; border:none; height:1px; margin:70px 0 60px;" />
        
        @endforeach

	@stop
