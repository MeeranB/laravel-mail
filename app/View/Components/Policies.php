<?php
 
namespace App\View\Components;
 
use Illuminate\View\Component;
 
class Policies extends Component
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
    public $policies;
    public $template;
    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($booking, $template, $policyArray = null)
    {
        $this->template = $template;

        $this->policies = [];

        $allPolicies = (object) [
            'No Show Policy:'=>$booking->property->textAssetByName('no_show')->value ?? null,
            'Additional T&C:'=>$booking->property->textAssetByName('terms_and_conditions')->value ?? null,
            'How to get there'=>$booking->property->textAssetByName('how_to_get_there')->value ?? null
        ];

        if (!$policyArray) {
            $this->policies = $allPolicies;
            return;
        }

        foreach($policyArray as $policyTitle) {
            $this->policies[$policyTitle] = $allPolicies->$policyTitle;
        }
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.policies');
    }
}