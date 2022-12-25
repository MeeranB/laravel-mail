<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class Header extends Component
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

    public $images;
    public $config;
    public $email;
    public $phone;
    public $logo;
    public $phoneImg;
    public $emailImg;
 
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($images, $config)
    {
        $this->email = $config->email;
        $this->phone = $config->phone_customers;
        $this->logo = $images['logo'];
        $this->phoneImg = $images['phone'];
        $this->emailImg = $images['email'];
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.header');
    }
}