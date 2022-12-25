<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class GuestNotes extends Component
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
    public $guestNotes;
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($booking)
    {
        $this->guestNotes = $booking->getGuestSpecialRequests();
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.guest-notes');
    }
}