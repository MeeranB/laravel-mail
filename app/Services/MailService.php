<?php
namespace App\Services;

use App\Mail\BookingPayment;
use App\Mail\UserAccount;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService {

    const THEME_CHICRETREATS         = 'chicretreats';
    const THEME_STAYBOOKED           = 'staybooked';
    const AVAILABLE_THEMES           = [self::THEME_CHICRETREATS, self::THEME_STAYBOOKED];

    const PASSWORD_USER_SET          = 'PasswordUserSet';
    const PASSWORD_USER_RESET        = 'PasswordUserReset';
    const USER_VERIFY_ACCOUNT        = 'UserVerifyAccount';

    const BOOKING_PAYMENT_COMPLETE   = 'BookingPaymentComplete';
    const BOOKING_PAYMENT_FAILED     = 'BookingPaymentFailed';

    const USER_TEMPLATES             = [self::PASSWORD_USER_SET, self::PASSWORD_USER_RESET, self::USER_VERIFY_ACCOUNT];
    const BOOKING_PAYMENT_TEMPLATES  = [self::BOOKING_PAYMENT_COMPLETE, self::BOOKING_PAYMENT_FAILED] ;           


    public function __construct($theme = self::THEME_CHICRETREATS) {
        //Mail service accepts a valid theme, else uses 'chicretreats' as default
        in_array($theme, self::AVAILABLE_THEMES) ? $this->theme = $theme : $this->theme = self::THEME_CHICRETREATS;
    }
    
    public function send($id, $template, $to) {

        try {
            if(in_array($template, self::USER_TEMPLATES)) {
                $mail = new UserAccount($id, $this->theme, $template);
            } elseif(in_array($template, self::BOOKING_PAYMENT_TEMPLATES)) {
                $mail = new BookingPayment($id, $this->theme, $template);
            } else {
                $model = sprintf('App\Mail\%s', $template);
                if(!class_exists($model)) throw new \Exception(sprintf('Unable to identify template: %s', $template));
                $mail = new $model($id, $this->theme);
            }
        } catch(\Exception $e) {
            Log::info($e->getMessage());
            return;
        }

        Mail::to($to)->send($mail->build());
    }
}
