<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class Room extends Component
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
     * @var string
     */
    // public $message;
    public $booking;
    public $room;
    public $propertyUrl;
    public $cancellationInterval;
    public $template;
    public $cancellationPolicies;
    
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($room, $propertyUrl, $booking, $template)
    {
        //Room is of type BookingDetail premises
        $this->room = $room;
        $this->template = $template;
        $this->propertyUrl = $propertyUrl;
        $this->booking = $booking;
        $this->generateAvailableTextAsset();
        $this->cancellationInterval = $this->booking->getCancellationInterval();
        $this->cancellationPolicies = $this->room->getCancellationPolicies();
        
        if ($this->cancellationPolicies) {
            foreach($this->cancellationPolicies as $cancellationPolicy) {
                if (isset($cancellationPolicy->from)) $cancellationPolicy->from = date_create($cancellationPolicy->from);
            }
        }
    }

    public function generateAvailableTextAsset() {

        if(!isset($this->room->propertyRoom)) return;

        foreach($this->room->propertyRoom->textAssets() as $asset) {
            if ($asset->name == 'description_short') {
                $this->room->textAsset = $asset->value;
            }
        }
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.room');
    }
}