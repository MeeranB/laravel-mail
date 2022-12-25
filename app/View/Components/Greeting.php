<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
use App\Models\Booking;
use App\Models\User;
use App\Models\UserQuery;



 
class Greeting extends Component
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
    public $template;
    public $user;
    public $userQuery;

    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct(Booking $booking = null, User $user = null, UserQuery $userQuery, $template)
    {
        $this->template = $template;
        $this->booking = $booking;
        $this->user = $user;
        $this->userQuery = $userQuery;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.greeting');
    }
}