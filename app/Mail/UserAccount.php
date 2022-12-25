<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\User;
use App\Services\MailService;

class UserAccount extends BaseTemplate {

    public function __construct(int $id, $theme = MailService::THEME_CHICRETREATS, $template) {

        //Set template here so parent constructor does not override $template with class name
        $this->template = $template;
        
        //Adds default template data
        parent::__construct($theme);
        
        $this->user = User::findOrFail($id);
        
        $data = [
            //Extend this to change discrepant data sent to respective mail templates
            'PasswordUserReset' => [
                'url' => sprintf('%sreset-password/%s', $this->config->base_url, $this->user->confirmation_token),
                'bannerTitle' => 'Password reset request',
                'buttonText' => 'Reset password'
            ],
            'PasswordUserSet' => [
                'url' => sprintf('%sreset-password/%s', $this->config->base_url, $this->user->confirmation_token),
                'bannerTitle' => 'Set your password',
                'buttonText' => 'Set your password'
            ],
            'UserVerifyAccount' => [
                'url' => sprintf('%suser/verify/%s', $this->config->base_url, $this->user->confirmation_token),
                'bannerTitle' => 'Account Verification Request',
                'buttonText' => 'Activate Account'
            ]
        ];

        $this->data = $data[$template];
    }

    public function build() {

        return $this->markdown('mail.templates.UserAccount')
                    ->with([
                        'user'=>$this->user,
                        'config' => $this->config,
                        'images' => $this->images,
                        'template' => $this->template,
                        'data' => $this->data
                    ])
                    ->subject($this->data['bannerTitle']);
    }
}