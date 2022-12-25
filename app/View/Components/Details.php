<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class Details extends Component
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
    public $fullName;
    public $propertyUrl;
    public $propertyBanner;
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($booking, $config)
    {
        $this->booking = $booking;
        $this->fullName = $booking->getSubmitterFullName();
        $this->propertyBanner = $this->booking->getDisplayImage();
        $this->propertyUrl = sprintf('%s%s', $config->base_url, $booking->property->getPropertySlug());
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.details');
    }
}