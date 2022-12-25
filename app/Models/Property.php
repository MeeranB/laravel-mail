<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\File;
use App\Models\TextAsset;
use App\Models\ListAsset;

class Property extends Model {

    protected $table = 'property';
    protected $appends = ['isChic', 'isExternalSupplier'];
    protected $hidden = ['id', 'property_type_group_id', 'source', 'source_id', 'stamp_created', 'stamp_updated', 'flag_extranet', 'flag_use_default_address_for_billing', 'vat_number', 'website', 'cmg_uid', 'cmg_channel', 'flag_cmg_issues', 'property_location_group_id', 'flag_locked_for_updates', 'flag_not_vat_registered', 'flag_valid', 'flag_pending_request', 'flag_has_valid_card', 'flag_has_overdue_invoices', 'flag_use_cardflow', 'card_expire_date', 'missing_card_notification_stamp', 'flag_imported', 'flag_enable_products', 'datetime_updated', 'google_commission', 'group_set_group'];

    public $timestamps = false;

    public function currency() {
        return $this->hasOne('App\Models\Currency', 'id', 'service_currency_country_id');
    }

    public function categories() {
        return $this->belongsToMany('App\Models\Category', 'property_category');
    }

    public function getLastChildCategory() {
        return $this->categories()->orderBy('level', 'DESC')->first();
    }

    public function sources() {
        return $this->hasMany('App\Models\Source');
    }

    public function contacts() {
        return $this->hasMany('App\Models\PropertyContact');
    }

    public function contactByType($name) {
        $contacts = $this->contacts->load(['typeGroups' => function($query) use ($name) {
            $query->where('name', $name);
        }]);

        return $contacts->filter(function($contact) {
            return $contact->typeGroups->count() > 0;
        });
    }

    public function contracts() {
        return $this->hasMany('App\Models\PropertyContract');
    }

    public function addresses() {
        return $this->hasMany('App\Models\PropertyAddress');
    }

    public function images() {
        return File::where([
            ['object_id', '=', $this->id],
            ['object_type_id', '=', 'property:image']
        ])->orderBy('sort_priority', 'ASC')->get();
    }

    public function slider() {
        return $this->hasOne('App\Models\File', 'object_id')->where(['object_type_id' => 'property:image', 'mask_type' => 1])->orderBy('sort_priority', 'ASC');
    }

    public function listbox() {
        return $this->hasOne('App\Models\File', 'object_id')->where(['object_type_id' => 'property:image'])->whereIn('mask_type', [2, 3, 6, 7])->orderBy('sort_priority', 'ASC');
    }

    public function googlePlaces() {
        return $this->hasMany('App\Models\PropertyGooglePlace');
    }    

    public function relations() {
        return $this->hasMany('App\Models\PropertyClientRelation');
    }

    public function clients() {
        return $this->hasManyThrough('App\Models\GroupSetGroup', 'App\Models\PropertyClientRelation', 'property_id', 'id', 'id', 'group_id');
    }

    public function groupSetGroup() {
        return $this->belongsToMany('App\Models\GroupSetGroup', 'property_client_relation', 'property_id', 'group_id');
    }

    public function groups() {
        return $this->hasMany('App\Models\PropertyGroup');
    }

    public function rooms() {
        return $this->hasMany('App\Models\PropertyRoom');
    }
    
    public function discounts() {
        return $this->hasMany('App\Models\Discount')->orderBy('created', 'DESC')->first();
    }

    public function discount() {
        return $this->hasOneThrough('App\Models\GroupSetGroup', 'App\Models\Discount', 'property_id', 'id', 'id', 'group_id');
    }

    public function textAssets() {
        return TextAsset::where(['object_id' => $this->id, 'object_type_id' => 'property'])->get();
    }

    public function textAssetByName($name) {
        return $this->textAssets()->where('name', $name)->first();
    }

    public function listAssets() {
        return ListAsset::where(['object_id' => $this->id, 'object_type_id' => 'property']);
    }

    public function getIsChicAttribute() {
        return count($this->groupSetGroup->where('id', env('CHICRETREATS_GROUP_ID'))) > 0;
    }

    public function getIsExternalSupplierAttribute() {
        return $this->sources->where('type', 'internal')->where('flag_primary', 1)->first() == null;
        // return count($this->groupSetGroup->where('id', env('CHICRETREATS_GROUP_ID'))) > 0;
    }

    public function getListAssetsbyName($name) {

        foreach ($this->listAssets() as $asset) {
            if ($asset->name == $name) {
                return $asset->value;
            }
        }
        return null;
    }

    public function getPrimarySource() {
        return $this->sources->firstWhere('flag_primary', '1');
    }

    public function getPropertyPhone() {

        if ($this->contactByType('reservations')->count() > 0) {
            return $this->contactByType('reservations')->phone_1 ??
                   $this->contactByType('reservations')->phone_2 ??
                   $this->contactByType('reservations')->phone_3;
        }

        return $this->telephone;
    }

    public function getPropertyEmail() {
        return $this->email ?? $this->contactByType('reservations')->email ?? null;
    }

    public function getPropertySlug() {
        return $this->slug;
    }
    
    public function createTaxonomy() {
        return [
            'from_rate' => $this->from_rate,
            'from_rate_currency' => 'GBP',
            'name' => $this->name,
            'slug' => $this->slug,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'destinations' => $this->categories->pluck('id')
        ];
    }

    public function createMeta($sourceType) {
        
        return [
            'propertyCode' => $this->sources->where('type', $sourceType)->pluck('source_id')->first(),
            'brand' => $this->isChic ? 'ChicRetreats' : 'StayBooked',
            'destinationUrl' => sprintf('https://www.%s.com/query/%s', $this->isChic ? 'chicretreats' : 'staybooked', $this->slug),
            'name' => $this->name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'website' => $this->website,
            'googleCommission' => $this->google_commission,
            'address' => $this->addresses && $this->addresses->count() > 0 ? $this->addresses->first()->createMeta() : null,
            'mainReservationLine' => $this->contacts->sortByDesc('typeRelations.group_id')->pluck('phone_1')->first()
        ];
    }

    public function propertyFacilities() {
        return $this->hasMany('App\Models\PropertyFacility', 'object_id');
    }
}
