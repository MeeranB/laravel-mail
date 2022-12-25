<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width"/>
    <style type="text/css">
    @media only screen {
        html {
            min-height: 100%;
            background: #fcfcfc;
        }
    }
    @media only screen and (max-width: 716px) {

        #footer .display-mobile {
            display: inline !important;
            padding: 0px 8px !important;
        }

        .column-type--mobile {
            width: 100% !important;
        }

        .display-block--mobile {
            display: block !important;
            padding-left: 0 !important;
        }

        .column {
            display: block !important;
            width: 100% !important;
        }
        .link-button {
            width: 100% !important;
            float:none !important;
            padding: 10px 0px !important;
        }
        .mobile-center {
            text-align: center !important;
        }
        .mobile-space-vertically {
            padding-top: 10px !important;
        }
        .logo {
            display: inline !important;
        }
        .transparent-container {
            background-color: white !important;
        }
    }
    </style>
</head>

<x-email-container
    :images='$images' 
    :config='$config'
    :theme='$theme'>
    <x-header 
        :images='$images' 
        :config='$config'>
    </x-header>
    <x-banner>
        <x-slot name='title'>
            {{ $data['bannerTitle'] }}
        </x-slot>
    </x-banner>
    <x-greeting
        :booking='$booking'
        :template='$template'>
    </x-greeting>
    <x-booking-table 
        :booking='$booking' 
        :table='$table'>
        <x-slot name="title">
            Booking Details
        </x-slot>
    </x-booking-table>
    <x-call-to-action>
        <x-slot name='text'>
            {{ $data['buttonText'] }}
        </x-slot>
        <x-slot name='buttonUrl'>
            {{ $data['url'] }}
        </x-slot>
    </x-call-to-action>
    <x-chicTreats
        :booking='$booking'>
    </x-chicTreats>
    <x-rate-comments
        :booking='$booking'>
    </x-rate-comments>
    <x-guest-notes
        :booking='$booking'>
    </x-guest-notes>
    <x-policies
        :booking='$booking'
        :template='$template'
        :policyArray='$policies'>
    </x-policies>
    <x-signature
        :template='$template'
        :config='$config'>
    </x-signature>
</x-email-container>


