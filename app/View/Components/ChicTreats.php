<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class ChicTreats extends Component
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
    public $isChicTreats;
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($booking)
    {
        $this->isChicTreats = json_decode($booking->property->getListAssetsbyName('chic_treats'));
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chicTreats');
    }
}