@extends('x')

@section('meta_title','your account has created please activate your account')

@section('content')

   <div style="background-color:#e7e7e7;">
        <div style="background-color:white;color:blue;padding:2px;">
            <h1>Dear {{$name}} you have registerd with {{$website}}!</h1>
            <p>{{$title}}</p>
            <p>
                you have recieve this email because you have make a register 
                to {{$website}} and we need your confirmation that 
                "is this you?" if you're sure that you really want to be a 
                member of our website {$website}} so please click the 
                confirmation link below.
    
            </p>
            <p>
                <a href="{{$link}}" target="_blank">Click here</a> 
            to open link in the new tab or copy the link below  
            </p>

            <p style="margin-top:4px;margin-botton:4px;color:blue;">
                {{$link}}</p>

            <p>
            or copy this below code to paste into the confirmation box when 
            you log-in to your account. 
            </p>
            <pre style="margin-top:4px;padding:4px;">
                <code style="background-color:#e7e7e7;color:#000000;">
                    <span>
                        {{$code}}
                    </span>
                </code>
            </pre>
            <p>
                if this is not you please ignore this message.
            </p>
            <div 
            style="background-color:black;color:red;margin-top:4px;
            margin-bottom:4px;padding:4px;border:2px dotted #ff0000;">
                <div style="padding:2px;">
                    <p>
                        this message template has wrote on 5 Feb 2022 by farook.
                    </p>
                    <p>
                        contact farook : farookphuket@gmail.com<br/>
admin@farookphuket.com,firefrook@gmail.com or tel : +66 95 9543920
                    </p>
                </div>
            </div>
        </div>
   </div>




@endsection
