<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\UserQuery as UserQueryModel;
use App\Services\MailService;

class UserQueryConfirm extends BaseTemplate {

    public function __construct(int $id, $theme = MailService::THEME_CHICRETREATS) {
        
        //Adds default template data
        parent::__construct($theme);

        $this->userQuery = UserQueryModel::findOrFail($id);
    }

    public function build() {

        return $this->markdown('mail.templates.' . $this->template)
                    ->with([
                        'userQuery' =>$this->userQuery,
                        'config' => $this->config,
                        'images' => $this->images,
                        'template' => $this->template,
                    ])
                    ->subject('Contact form confirmation');
    }
} 