<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class Footer extends Component
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
    public $phone;
    public $theme;
 
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($images, $config, $theme)
    {
        $this->theme = $theme;
        $this->images = $images;
        $this->config = $config;
        $this->phone = $config->phone_customers;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.footer');
    }
}