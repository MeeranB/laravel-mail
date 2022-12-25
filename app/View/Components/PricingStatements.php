<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class PricingStatements extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    // public $type;

    /**
     * The alert message.
     *
     * @var object
     */
    public $booking;
    public $cancellationDate;
    public $hasScheduledPayment;
    public $isDifferentTransactional;
    public $discountAmount;
    public $statements;
    public $table;


    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($booking, $template)
    {
        $this->booking = $booking;
        $this->cancellationDate = $booking->getCancellationDate();
        $this->hasScheduledPayment = $booking->hasScheduledPayment();
        $this->isDifferentTransactional = $booking->currency_id !== $booking->transactional_currency_id;
        $this->discountAmount = $booking->getDiscountAmount();
        $this->statements = $this->routePricingStatements($template);
    }

    //Function that returns array containing objects with key, value, and visible properties

    public function getPricingStatements() {
        return (object)[
            'paymentScheduled' => (object)[
                'value' => sprintf(
                    'Please note: The balance will be taken on %s using the credit/debit card you have originally booked with',
                    $this->cancellationDate),
                'isVisible' => $this->cancellationDate > date('Y-m-d') && $this->hasScheduledPayment
            ],
            'isDifferentTransactional' => (object)[
                'value' => sprintf(
                    '<small>* Converted from %1$s %3$s at the exchange rate of %1$s 1 = %2$s %4$s provided by European inter banking rates. The converted price in %5$s may differ dependent on the exchange rate used by the hotel.</small>', 
                    $this->booking->currency->getSymbolOrCode(), 
                    $this->booking->transactionCurrency->getSymbolOrCode(), 
                    number_format($this->booking->total, 2, '.', ''), 
                    number_format($this->booking->exchange_rate, 4, '.', ''),
                    $this->booking->transactionCurrency->getSymbolOrCode()
                ),
                'isVisible' => $this->isDifferentTransactional
            ],
            'fluctuatingPrice' => (object)[
                'value' => sprintf(
                    'Please note: at the hotel you will be quoted the price in %s. The converted price in %s may differ at the time of your check-out due to fluctuations in the exchange rate',
                    $this->booking->currency->currency_name, $this->booking->transactionCurrency->currency_name
                ),
                'isVisible' => $this->isDifferentTransactional && $this->booking->payments_processor !== 1
            ],
            'cancellationPenaltyFees' => (object)[
                'value' => '** In case of cancellation, penalty fees will be calculated based on the original value (before discount) and any refund (if applicable) is capped by the deposit paid',
                'isVisible' => $this->discountAmount > 0 && $this->booking->getDetailsDiscountAmount() > 0
            ],
            'commissionAssurance' => (object)[
                'value' => '** Taken out of Chic Retreats\' commission',
                'isVisible' => $this->booking->getDiscountAmount()
            ],
            'paymentDetails' => (object)[
                'value' => '*Payment details can be obtained (one time only) through our extranet',
                'isVisible' => $this->booking->paymentProcessor == 2 && $this->booking->getTransactionalBalanceDue()
            ]
        ];
    }

    //Create function that generates the pricing statements table based on template it is being used within

    public function routePricingStatements($template) {

        $layouts = (object)[
            'BookingUserComplete' => [ 
                'paymentScheduled',
                'isDifferentTransactional',
                'fluctuatingPrice',
                'cancellationPenaltyFees'
            ],
            'BookingUserCancelled' => [
                'cancellationPenaltyFees',
                'isDifferentTransactional'
            ],
            'BookingPropertyComplete' => [
                'commissionAssurance',
                'paymentDetails'
            ],
            'BookingPropertyCancelled' => [
                'commissionAssurance',
            ],
        ];

        return $layouts->$template;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pricing-statements');
    }
}