<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\BookingPayment;
use App\Models\BookingDetail;


use Carbon\Carbon;

class Booking extends Model {

    const STATUS_CREATED                            = 0;
    const STATUS_CONFIRMED                          = 10;
    const STATUS_CANCELLED                          = 100;    

    const SUPPLIER_MAP = [
        'consortia'=> ['CON', 'CONSORTIA'],
        'hotelbeds'=> ['HB'],
        'jumbo'=>['JUMBO'],
        'ots'=>['OTS'],
        'teygon'=>['TEYGON']
    ];

    const CLIENT_MAP = [
        'staybooked' => ['sbcst'],
        'chicretreats' => ['chicst, chicp1'],
        'airsmiles' => ['airst'],
        'posh' => ['pufsct']
    ];

    protected $table = 'booking';

    public $timestamps = false;

    

    // Temp fix to hold asset information from suppliers
    public $request;
    public $response;

    public function property() {
        return $this->belongsTo('App\Models\Property');
    }

    public function details() {
        return $this->hasMany('App\Models\BookingDetail');
    }

    public function getSubmitterFullName() {
        return ucwords(join(' ', [$this->guest->salutation, $this->guest->first_name, $this->guest->last_name]));
    }
    
    public function roomDetails() {
        return $this->hasMany('App\Models\BookingDetail')->whereNotIn('type', [BookingDetail::TYPE_OTHER, BookingDetail::TYPE_SERVICE, BookingDetail::TYPE_TAX]);
    }

    public function assets() {
        return $this->hasMany('App\Models\BookingAsset');
    }

    public function assetByName($name) {
        return $this->assets->where('name', $name)->first() ?? null;
    }

    public function payments() {
        return $this->hasMany('App\Models\BookingPayment');
    }

    public function notes() {
        return $this->hasMany('App\Models\BookingNote');
    }
    
    public function currency() {
        return $this->hasOne('App\Models\Currency', 'id', 'currency_id');
    }

    public function transactionCurrency() {
        return $this->hasOne('App\Models\Currency', 'id', 'transactional_currency_id');
    }

    public function isOrigin(string $origin) {
        return $this->source === $origin ? true : false;
    }

    public function guest() {
        return $this->hasOne('App\Models\Guest', 'id', 'submitter_id');
    }

    public function guests() {
        return $this->hasMany('App\Models\Guest', 'object_id', 'id');
    }

    public function getClient() {
        //Example:  'client:chicst | supplier:CON' to 'chicst' to 'chicretreats'

        $source = explode('|', $this->source);
        $clientCode = explode(':', $source[0])[1] ?? NULL;

        if (empty($clientCode)) return 'chicretreats';

        foreach(self::CLIENT_MAP as $client => $validCodes) {
            if (in_array($clientCode, $validCodes)) return $client;
        }

        return 'chicretreats';
    }

    public function getSupplier() {
        //Example:  'client:chicst | supplier:CON' to 'CON' to 'consortia'

        $source = explode('|', $this->source);
        $supplierCode = count($source) > 1 ? explode(':', $source[1])[1] : NULL;

        if (empty($supplierCode)) return null;

        foreach(self::SUPPLIER_MAP as $supplier => $validCodes) {
            if (in_array($supplierCode, $validCodes)) return $supplier;
        }
        return 'teygon';
    }

    public function calculateNights() {
        if (is_null($this->date_from) || is_null($this->date_to)) return; 

        $dateFrom = Carbon::parse($this->date_from);
        $dateTo = Carbon::parse($this->date_to);

        return $dateFrom->diffInDays($dateTo);
    }

    public function getTotalPaid(bool $isTransactionalFlag = false, bool $excludeDeposit = false): ?float {
        $types = $excludeDeposit ? [BookingPayment::TRANSACTION_TYPE_PAYMENT] : [BookingPayment::TRANSACTION_TYPE_PAYMENT, BookingPayment::TRANSACTION_TYPE_DEPOSIT];
        $column = $isTransactionalFlag ? 'transactional_amount' : 'amount';

        return $this
            ->payments
            ->whereIn('transaction_type', $types)
            ->where('status', BookingPayment::STATUS_COMPLETED)
            ->sum($column) / 100;
    }


    public function getTransactionalTotal(): float {
        //Excludes transactional booking deposit amount
        //Use booking total field, unless booking was received in different currency, then sum bookingdetails and convert them with the relevant exchange rate

        return ($this->currency_id === $this->transactional_currency_id) ? 
        $this->total : $this->total * $this->exchange_rate;
    }

    public function getDetailsTotal(){
        $total = 0;
        foreach($this->details as $bookingDetail) {
            if(!$bookingDetail->getAssetByName('supplier_room_offer')->first()) continue;
           $total += json_decode($bookingDetail->getAssetByName('supplier_room_offer')->first()->value)->rate->total ?? 0;
        }
        return $total;
    }

    public function getRefundedTotal(bool $isTransactionalFlag): float {

        $value = $isTransactionalFlag ? 'transactional_amount' : 'amount';

        $total = $this->payments
                            ->where('status', BookingPayment::STATUS_COMPLETED)
                            ->where('transaction_type', BookingPayment::TRANSACTION_TYPE_REFUND)
                            ->sum($value);

        return $total / 100;
    }

    public function getDiscountAmount(): float {
        return $this->details->sum('discount_amount');
    }

    public function getDetailsDiscountAmount() {
        $discountAmount = 0;

        foreach ($this->details as $detail) {
            $asset = $detail->assets->firstWhere('name', 'supplier_room_offer');
            if (!$asset) continue;
            $discountAmount += json_decode($asset->value)->discountAmount ?? 0;
        }

        return $discountAmount;
    }

    public function getDepositPaid(bool $isTransactionalFlag, bool $checkPayments = true) {

        //Utilising deposit_paid field from bookings model may cause inaccuracies compared to checking the related payments for the booking
        //TL;DR Recommended to set $checkPayments flag to true
        if ($checkPayments == false) {
            return $isTransactionalFlag ? $this->deposit_paid * $this->exchange_rate : $this->deposit_paid;
        }

        $total = $this
                    ->payments
                    ->where('transaction_type', BookingPayment::TRANSACTION_TYPE_DEPOSIT)
                    ->where('status', BookingPayment::STATUS_COMPLETED)
                    ->sum('amount') / 100;


        return $isTransactionalFlag ? $total * $this->exchange_rate : $total;
    }

    public function getTransactionalDepositRequired(): float {

        $deposit = $this->getDepositRequired();

        $transactionalDeposit = 0;
        $relevantPayments = $this->payments->whereIn('transaction_type', [BookingPayment::TRANSACTION_TYPE_DEPOSIT, BookingPayment::TRANSACTION_TYPE_VALIDATION]);

        foreach($relevantPayments as $payment) {
            $transactionalDeposit += $payment->transactional_amount / 100;
        }

        if((float)$deposit === (float)$transactionalDeposit) return $deposit;

        return floatval($deposit) * $this->exchange_rate;
    }

    public function getDepositRequired(): float {

        $depositRequired = $this
                            ->payments
                            ->whereIn('transaction_type', [BookingPayment::TRANSACTION_TYPE_DEPOSIT, BookingPayment::TRANSACTION_TYPE_VALIDATION])
                            ->sum('amount') / 100;

        return $depositRequired ?? $this->deposit_required;
    }

    public function getTotalToPay() {
        //dbtotal - gettotalpaid
    }

    public function hasScheduledPayment() {
        return $this->payments->contains('status', BookingPayment::STATUS_SCHEDULED);
    }

    public function getCancellationDate() {
        //Gets earliest cancellationDate, if date is later than this then there is liabilities for penalties through the cancellation policies
        $date = NULL;

        foreach($this->details as $bookingDetail) {
            $asset = $bookingDetail->getAssetByName('supplier_room_offer')->first();
            if (!$asset) continue;
            $assetValues = json_decode($asset->value);

            if($assetValues->cancellationDate) {
                if(!$date) $date = $assetValues->cancellationDate;
                $date = $assetValues->cancellationDate < $date ? $assetValues->cancellationDate : $date;
            }
        }

        if(!$date) return null;

        $date = date("Y-m-d", strtotime($date));

        return $date;
    } 

    public function getTransactionalBalanceDue(): float {
        //Booking model
        if ($this->currency_id === $this->transactional_currency_id) {
            //if visitor and hotel currency is same, use balanceDue to calc balance due.
            return $this->getBalanceDue();
        }
        
        if ($this->status === Booking::STATUS_CANCELLED) {

            //TODO: Update $topay to utilise penalties in future (in transaction currency)
            //If booking is cancelled, penalties must be paid based on time booking was cancelled
            //$topay = $this->getPenaltiesTotal($this->getStampCancelled()) * $this->exchange_rate

            //TODO: Include Transactional penalties already paid; paid currently consists of:
            //TransactionalTotalPaid, excluding penalties, including deposit
            //Minus the transactional amount the system has returned to them prior to cancellation (when booking is cancelled, we claim any booking refunds back)
            //The customer owes us the amount we have refunded them throughout the process if the booking was cancelled
            $paid = $this->getTotalPaid(true, false) - $this->getRefundedTotal(true);

            //TODO: Include $topay
            //return $topay - $paid
            return 0 - $paid;
        }
        //transactional total cost of booking minus depositrequired - total booking details discount amount
        $bookingTotal = (round($this->getTransactionalTotal(), 2) - $this->getDiscountAmount());

        return round($bookingTotal - $this->getTotalPaid(true, false), 2);
    }

    public function getBalanceDue() {
        //Booking model
        if ($this->status === Booking::STATUS_CANCELLED) {
            // $topay = $this->getPenaltiesTotal($this->getStampCancelled());
            $topay = 0;
            // Calculate the total penalties on the booking when it was cancelled by checking the booking details and using the most recent penalty that is less than current date

            // $paid = $this->getPenaltiesPaid()
            // Calculate penalties paid by summing all completed payments with a penalty flag, add this to $paid

            //Totalpaid will be retrieved conditionally with deposit based on current calculations
            $paid = $this->getTotalPaid(false) - $this->getRefundedTotal(false);

            return $topay - $paid;
        }

        $bookingTotal = (round($this->total, 2) - $this->getDiscountAmount());
        return round($bookingTotal - $this->getTotalPaid(false), 2);
    }

    public function getNetAmount() {
        return $this->getDetailsCommissionAmount() == 0 ? round($this->total - $this->commission, 2): round($this->total - $this->getDetailsCommissionAmount(), 2);
    }

    public function getDetailsCommissionAmount() {
        $commission = 0;
        foreach ($this->details as $detail) {
            $asset = $detail->assets->firstWhere('name', 'supplier_room_offer');
            if (!$asset) continue;
            $commission += json_decode($asset->value)->rate->commissionAmount ?? 0;
        }
        return $commission;
    }

    public function getGuestSpecialRequests() {
        //Loop over booking notes
        //Booking Model, gathers all booking note comments and combines them
        $bookingNotes = $this->notes;
        $submitterNotes = [];

        foreach ($bookingNotes->where('type_id', '=', BookingNote::TYPE_SUBMITTER) as $note) {
            $submitterNotes[] = $note->content;
        }
        //Return note comments if it is of type submitter
        return $submitterNotes;
    }

    public function getRateComments() {
        //Booking Model
        $bookingDetails = $this->details;

        $comments = [];

        foreach ($bookingDetails as $bookingDetail) {
            foreach($bookingDetail->assets as $asset) {
                $assetJSON = json_decode($asset->value);
                //Find comments based on JSON structure
                //comments may already have description/text format.
                if (isset($assetJSON->comments)) {
                    foreach($assetJSON->comments as $comment) {
                        $comments[] = $comment;
                    }
                } 
                if (isset($assetJSON->rate->comments)) {
                    foreach($assetJSON->rate->comments as $comment) {
                        $comments[] = (object)[
                            'description' => is_object($comment) ? $comment->type : null,
                            'text' => is_object($comment) ? $comment->text : $comment
                        ];
                    }
                }
            }
        }
        return $comments;
    }

    public function getCancellationAmount($date) {
        //Takes a date and examines booking cancellation policies to return the appropriate cancellation fee.

        // Format date to 'Y-m-d H:i:s' to match policy for date comparison 
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if (!$date) return null;

        //If the booking does not have a cancellation policy in the first bookingdetail's first active asset, return 0.
        $availablePolicies = $this
                                ->details
                                ->first()
                                ->assets
                                ->whereIn('name', BookingDetailAsset::ACTIVE_NAMES)
                                ->sortByDesc('id', SORT_NUMERIC)
                                ->first()
                                ->getCancellationPolicies() ?? null;
        if(!$availablePolicies) return 0;

        // Pick cancellation policies with valid key value pairs
        $validPolicies = $availablePolicies->filter(function($policy) {
            return array_key_exists('from', $policy) && array_key_exists('amount', $policy);
        });

        if (!$validPolicies) return 0;

        //Sort cancellation policies by 'from' date in descending order
        $validPolicies = $validPolicies->sortByDesc('from')->values();

        //Pick first policy that is before given date, if all policies are before given date, return 0.
        $applicablePolicy = $validPolicies->firstWhere('from', '<=', $date->format('Y-m-d H:i:s'));
        
        return $applicablePolicy->amount ?? 0;
    }

    public function getCancellationInterval() {
        // Get first cancellationDate where $room->getLastActiveAsset()->getCancellationDate() is not NULL
        $cancellationRoom = $this->roomDetails->sortByDesc('id', SORT_NUMERIC)->first(function($room) {
            if (!$room->getLastActiveAsset()) return false;
            return ($room->getLastActiveAsset()->getCancellationDate() ?? false);
        }) ?? null;

        if (!$cancellationRoom) return null;

        $cancellationDate = $cancellationRoom->getLastActiveAsset()->getCancellationDate();

        $now = new \DateTime('now');
        $interval = $now->diff(new \DateTime($cancellationDate));
        return $interval->days;
    }

    public function getMealPlan() {
        //Note from Extranet implementation: 'this method works only in cmg hotels where we could have many rooms but this same type and this same mealPlan'
        $mealPlan = null;

        foreach($this->details as $detail) {
            $mealPlanAsset = $detail->assets->firstWhere('name', 'cmg_json_offer') ?? null;
            if (!$mealPlanAsset) continue;
            $mealPlan = json_decode($mealPlanAsset->value)->mealPlan ?? null;
            if (!$mealPlan) continue;
        }

        return $mealPlan;
    }


    public function getCommissionlessTotal() {
        return $this->total / (1 + $this->commission / 100);
    }

    public function isPaidOff() {
        return $this->total - $this->getTotalPaid() == 0;
    }

    public function isDirect() {
        return ($this->property->getPrimarySource()->type === 'internal' || $this->property->getPrimarySource()->type === 'consortia' || $this->payments_processor !== 1) && !$this->hasScheduledPayment() && $this->getTransactionalBalanceDue() > 0;
    }

    public function getDisplayImage() {
        // $img = $this->property->slider ? $this->property->slider->url : $this->property->images()->first()->url;
        // return $img ?? null;

        return $this->property->images()->first()->url;
    }

}
