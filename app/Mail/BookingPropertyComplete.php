<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\Booking;
use App\Services\MailService;

use Carbon\Carbon;

class BookingPropertyComplete extends BaseTemplate {

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


        $isConsortia = $this->booking->getSupplier() == 'consortia';
        $currencySymbol = $this->booking->currency->getSymbolOrCode();

        return [
            'left' => [
                'Booking Date:' => Carbon::parse($this->booking->stamp_created)->format('j M Y'),
                'Hotel Name:' => $this->booking->property->name,
                'Guest Name:' => $this->booking->getSubmitterFullName(),
                'Adults:' => $this->booking->adults ?? 0,
                'Children:' => $this->booking->children ? sprintf('%d (%d)', $this->booking->children, $this->booking->children_ages) : false,
                'Rooms:' => $this->booking->roomDetails->count(),
                'Board Basis:' => $this->booking->getMealPlan() ?? 'none'
            ],
            'right'=> [
                'Check-In:' => Carbon::parse($this->booking->date_from)->format('D d M Y'),
                'Check-Out:' => Carbon::parse($this->booking->date_to)->format('D d M Y'),
                'Nights:' => $this->booking->calculateNights() ?? '-',
                'Total:' => $isConsortia ? sprintf('%s %s', $currencySymbol, number_format($this->booking->getNetAmount(), 2, '.', '')) : sprintf('%s %s', $currencySymbol, number_format($this->booking->total, 2, '.', '')),
                'Discount**:' =>$this->booking->getDiscountAmount() ? sprintf('%s %s', $currencySymbol, number_format($this->booking->getDiscountAmount(),2, '.', '')) : false,
                'Deposit (to be taken):' => $this->booking->payment_processor == 2 && $this->booking->getTransactionalBalanceDue() > 0 ? sprintf('%s %s', $currencySymbol, number_format($this->booking->getDepositRequired(), 2, '.', '')) : false,
                'Due At Hotel:' => !$isConsortia && !$this->booking->hasScheduledPayment() && $this->booking->getTransactionalBalanceDue() > 0 ? sprintf('%s %s', $currencySymbol, number_format($this->booking->getBalanceDue(), 2, '.', '')) : false
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
                        'url' => sprintf('%s/property/%s/booking/%s', $this->config->base_url, $this->booking->property->slug, $this->booking->uid),
                        'propertyUrl' => sprintf('%s%s', $this->config->base_url, $this->booking->property->getPropertySlug())
                    ])
                    ->subject(sprintf('New booking %s', $this->booking->uid));
    }
}