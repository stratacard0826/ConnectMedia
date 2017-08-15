<!-- resources/views/emails/logout.blade.php -->
	
    @extends('layouts.email')
    @section('type', 'Medical Referral ' . @$type )
    @section('subject', date( 'l, M d, Y' , strtotime( @$data->created_at ) ) )

    @section('header-background',  asset('public/assets/images/email/buyerslogout.png'))
    @section('header-title',  'MEDICAL REFERRAL')
    @section('header-title-background',  '#f1aa00')
    @section('header-big-title',  'MEDICAL REFERRAL CREATED')
    @section('header-small-title', date( 'l, M d, Y' , strtotime( @$data->created_at ) ) )

    @section('content')

        <h4 style="font-size:20px; margin-bottom:0;">Dear Dr. {{ @$doctor->lastname }},</h4>

        <p style="font-size:15px; line-height:160%;">{{ @$data->customer }} has just visited {{ @$store->name }} with a referral from your office.</p>

        <p style="font-size:15px; line-height:160%;">{{ @$data->customer }} has purchased: </p>
        
        <ul>
            @if(@$products)
                @foreach( $products as $product )
                    <li style="font-size:15px; line-height:160%;">{{ @$product->pivot['product'] }}</li>
                @endforeach
            @endif
        </ul>

        <p style="font-size:15px; line-height:160%;">
            <strong>Notes:</strong> <br />
            {{ @$data->notes }}
        </p>

        <hr style="background:#EFEFEF; border:none; height:1px; margin:40px 0;" />

        <p style="line-height:160%; margin-bottom:20px;">
            If you or your patients have any questions or concerns, or want more information about their footwear and fitting, please feel free to contact me by email, or by phone at {{ env('PHONE') }}.
        </p>

        <p style="line-height:160%; margin-bottom:20px;">
            We value your referrals and your feedback. To order samples, prescription pads, or supplies from {{ env('COMPANY') }}, call us at: {{ env('PHONE') }}
        </p>

        <p style="line-height:160%;">
            Sincerely, <br />
            {{ env('COMPANY') }}
        </p>

        <p style="text-align:center;"><small>If you would prefer not to receive these notifications from us, please contact <a href="{{ env('WEBMASTER_EMAIL') }}">{{ env('WEBMASTER_NAME') }}</a></small></p>

	@stop
