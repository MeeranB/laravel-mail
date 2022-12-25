<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\Booking;
use App\Services\MailService;
use Carbon\Carbon;

class BookingUserCancelled extends BaseTemplate {

    public function __construct(int $id, $theme = MailService::THEME_CHICRETREATS) {

        //Adds default template data
        parent::__construct($theme);

        $this->booking = Booking::with([
            'guest',
            'details.propertyRoom.images',
            'currency',
            'transactionCurrency',
            'property' => function($query) {
                return $query->with([
                    'sources',
                    'slider',
                    'addresses.country']);
            }])->findOrFail($id);
    }

    private function emailTable() {

        $transactionalCurrencySymbol = $this->booking->transactionCurrency->getSymbolOrCode();

        return [
            'left' => [
                'Booking Date:' => Carbon::parse($this->booking->stamp_created)->format('j M Y'),
                'Email:' => $this->booking->guest->email ?? '-',
                'Phone:' => $this->booking->guest->phone1 ?? '-',
                'Adults:' => $this->booking->adults ?? 0,
                'Children:' => $this->booking->children ? sprintf('%d (%d)', $this->booking->children, $this->booking->children_ages) : 0,
                'Rooms:' => $this->booking->roomDetails->count(),
                'Board Basis:' => $this->booking->getMealPlan() ?? 'none'
            ],
            'right'=> [
                'Check-In:' => Carbon::parse($this->booking->date_from)->format('D d M Y'),
                'Check-Out:' => Carbon::parse($this->booking->date_to)->format('D d M Y'),
                'Nights:' => $this->booking->calculateNights() ?? '-',
                'Total:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format(number_format($this->booking->getTransactionalTotal(), 2, '.', '') - number_format($this->booking->getDiscountAmount(), 2, '.', ''), 2, '.', '')),
                'Discount**:' => $this->booking->getDiscountAmount() ? sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getDiscountAmount(),2, '.', '')) : false,
                'Deposit:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTransactionalDepositRequired(), 2, '.', '')),
                'Cancellation Fees:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format(number_format($this->booking->getCancellationAmount($this->booking->stamp_cancelled), 2, '.', '') * number_format($this->booking->exchange_rate, 2, '.', ''), 2, '.', '')),
                'Refund:' => $this->booking->getRefundedTotal(true) > 0 ? sprintf('%s %s', $transactionalCurrencySymbol, number_format(abs($this->booking->getRefundedTotal(true), 2, '.', ''))) : false
            ],
            'outline' => true
        ];
    }

    public function build() {

        return $this->markdown('mail.templates.' . $this->template)
                    ->with([
                        'booking'=>$this->booking,
                        'table' => $this->emailTable(),
                        'config' => $this->config,
                        'images' => $this->images,
                        'template' => $this->template,
                    ])
                    ->subject(sprintf('Cancellation of your booking %s', $this->booking->uid));
    }
}