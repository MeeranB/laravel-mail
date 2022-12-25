<?php

namespace App\Mail;

use App\Mail\BaseTemplate;
use App\Models\UserQuery as UserQueryModel;
use App\Services\MailService;

class UserQuery extends BaseTemplate {

    public function __construct(int $id, $theme = MailService::THEME_CHICRETREATS) {
        
        //Adds default template data
        parent::__construct($theme);

        $this->userQuery = UserQueryModel::findOrFail($id);
    }

    private function emailTable() {

        $createdDate = \DateTime::createFromFormat('Y-m-d H:i:s', $this->userQuery->stamp_created)->format('F j, Y G:i');

        return [
            'left' => [
                'User:' => $this->userQuery->getName(),
                'Email:' => $this->userQuery->email,
                'Phone:' => $this->userQuery->phone,
                'Call back?:' => $this->userQuery->flag_call_back ? 'yes' : 'no',
                'Message:' => $this->userQuery->message,
                'Created:' => $createdDate,
                'Referer:' => $this->userQuery->referer ?? '-'
            ],
            'right' => [],
            'outline' => false
        ];
    }


    public function build() {

        return $this->markdown('mail.templates.' . $this->template)
                    ->with([
                        'userQuery' =>$this->userQuery,
                        'config' => $this->config,
                        'images' => $this->images,
                        'table' => $this->emailTable(),
                        'template' => $this->template,
                    ])
                    ->subject('Message from contact form');
    }
} 