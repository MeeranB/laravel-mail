<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\Booking;
use App\Services\MailService;
use Carbon\Carbon;

class BookingUserComplete extends BaseTemplate {

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
                'Name:' => $this->booking->getSubmitterFullName() ?? '-',
                'Phone:' => $this->booking->guest->phone1 ?? '-',
                'Adults:' => $this->booking->adults ?? 0,
                'Children:' => $this->booking->children ? sprintf('%d (%d)', $this->booking->children, $this->booking->children_ages) : 0,
                'Rooms:' => $this->booking->roomDetails->count(),
                'Nights:' => $this->booking->calculateNights() ?? '-',
            ],
            'right'=> [
                'Check-In:' => Carbon::parse($this->booking->date_from)->format('D d M Y'),
                'Check-Out:' => Carbon::parse($this->booking->date_to)->format('D d M Y'),
                'Total:' => sprintf('%s %s', $transactionalCurrencySymbol, number_format(number_format($this->booking->getTransactionalTotal(), 2, '.', '') - number_format($this->booking->getDiscountAmount(), 2, '.', ''), 2, '.', '')),
                'Discount**:' => $this->booking->getDiscountAmount() ? sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getDiscountAmount(),2, '.', '')) : false,
                'Paid:' => !$this->booking->isPaidOff() && $this->booking->getDepositPaid(true, true) > 0 ? sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTransactionalDepositRequired(), 2, '.', '')) : sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTotalPaid(true), 2, '.', '')),
                'Due at Hotel:' => $this->booking->isDirect() ? sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTransactionalBalanceDue(), 2, '.', '')) : false,
                'Balance Due:' => !$this->booking->isDirect() ? sprintf('%s %s', $transactionalCurrencySymbol, number_format($this->booking->getTransactionalBalanceDue(), 2, '.', '')) : false
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
                        'spaUrl' => sprintf('%s/booking/%s', $this->config->base_url, $this->booking->uid),
                        'propertyUrl' => sprintf('%s%s', $this->config->base_url, $this->booking->property->getPropertySlug())
                    ])
                    ->subject(sprintf('Your booking at %s', $this->booking->property->name));
    }
}