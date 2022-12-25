<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\Booking;
use App\Services\MailService;
use Carbon\Carbon;

class BookingPayment extends BaseTemplate {

    public function __construct(int $id, $theme = MailService::THEME_CHICRETREATS, $template) {

        //Set template here so parent constructor does not override $template with class name
        $this->template = $template;

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

        $data = [
            //Extend this to change discrepant data sent to respective mail templates
            'BookingPaymentFailed' => [
                'url' => sprintf('%sadmin/bookings/booking/%s/payments', $this->config->base_url, $this->booking->uid),
                'bannerTitle' => 'Confirmation of Payment',
                'buttonText' => 'View your booking',
                'subject' => sprintf('Confirmation of Payment - %s', $this->booking->uid)
            ],
            'BookingPaymentComplete' => [
                'url' => sprintf('%sbooking/%s', $this->config->base_url, $this->booking->uid),
                'bannerTitle' => 'Notice of payment failure',
                'buttonText' => 'View Booking Payments',
                'subject' => sprintf('Failed payment attempt - %s', $this->booking->uid)
            ]
        ];

        $this->data = $data[$template];
    }

        private function emailTable() {

        $transactionalCurrencySymbol = $this->booking->transactionCurrency->getSymbolOrCode();

        return [
            'left' => [
                'Booking Date:' => Carbon::parse($this->booking->stamp_created)->format('j M Y'),
                'Name:' => $this->booking->getSubmitterFullName() ?? '-',
                'Phone:' => $this->booking->guest->phone1 ?? '-',
                'Adults:' => $this->booking->adults,
                'Children:' => $this->booking->children ? sprintf('%d (%d)', $this->booking->children, $this->booking->children_ages) : false,
                'Rooms:' => $this->booking->roomDetails->count(),
                'Board Basis:' => $this->booking->getMealPlan() ?? false
            ],
            'right'=> [
                'Check-In:' => Carbon::parse($this->booking->date_from)->format('D d M Y'),
                'Check-Out:' => Carbon::parse($this->booking->date_to)->format('D d M Y'),
                'Nights:' => $this->booking->calculateNights() ?? '-',
                'Booking Total:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTransactionalTotal(), 2, '.', '')),
                'Amount Paid:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTotalPaid(true), 2, '.', '')),
                'Deposit:' => !$this->booking->isPaidOff() ? sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getDepositRequired(), 2, '.', '')) : false,
                'Balance Due:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTransactionalBalanceDue(), 2, '.', ''))
            ],
            'outline' => true
        ];
    }

    public function build() {

        return $this->markdown('mail.templates.BookingPayment')
                    ->with([
                        'booking'=>$this->booking,
                        'table' => $this->emailTable(),
                        'config' => $this->config,
                        'images' => $this->images,
                        'template' => $this->template,
                        'policies' => ['How to get there'],
                        'data' => $this->data,
                    ])
                    ->subject($this->data['subject']);
    }

}