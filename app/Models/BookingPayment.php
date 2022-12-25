<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model {

    const STATUS_CREATED                    = 'CREATED';
    const STATUS_INITIATED                  = 'INITIATED';
    const STATUS_SCHEDULED                  = 'SCHEDULED';
    const STATUS_REMOTE_ERROR               = 'REMOTEERROR';
    const STATUS_REMOTE_CANCEL              = 'REMOTECANCEL';
    const STATUS_LOCAL_ERROR                = 'LOCALERROR';
    const STATUS_CANCELLED                  = 'CANCELLED';
    const STATUS_COMPLETED                  = 'COMPLETED';
    const STATUS_REFUNDED                   = 'REFUNDED';
    const STATUS_TOKENIZED                  = 'TOKENIZED';

    const TRANSACTION_TYPE_DEPOSIT          = 'DEPOSIT';
    const TRANSACTION_TYPE_PAYMENT          = 'PAYMENT';
    const TRANSACTION_TYPE_VALIDATION       = 'VALIDATION';
    const TRANSACTION_TYPE_TOKENIZATION     = 'TOKENIZATION';
    const TRANSACTION_TYPE_REFUND           = 'REFUND';
    const TRANSACTION_TYPE_CREDIT           = 'CREDIT';

    const TRANSACTION_ORIGIN_CUSTOMER       = 'CUSTOMER';
    const TRANSACTION_ORIGIN_SYSTEM         = 'SYSTEM';
    const TRANSACTION_ORIGIN_PARTNER        = 'PARTNER';
    const TRANSACTION_ORIGIN_REMOTE         = 'REMOTE';
    const TRANSACTION_ORIGIN_PROPERTY       = 'PROPERTY';
    const TRANSACTION_ORIGIN_ADMINISTRATOR  = 'ADMINISTRATOR';

    protected $table = 'booking_payment';

    public $timestamps = false;

}
