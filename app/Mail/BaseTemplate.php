<?php

namespace App\Mail;

use App\Services\MailService;
use Illuminate\Mail\Mailable;


class BaseTemplate extends Mailable {

    public $config;
    public $images;
    public $template;
    public $theme;

    public function __construct($theme = MailService::THEME_CHICRETREATS) {
        $this->config = json_decode(file_get_contents(storage_path('app/mail.config.json')))->$theme;
        $this->theme = $theme;
        $path = sprintf('%s/img/mail/%s', getenv('APP_URL'), $this->theme);
        // $path = sprintf('%s/img/mail/%s', $this->config->base_url, $theme);
        // $path = sprintf('%s/bundles/mail/images/emails', $this->config->base_url);
        $baseMailData = array(
            'background' => $path . '/background.png',
            'cursor' => $path . '/cursor.png',
            'facebook' => $path . '/facebook.png',
            'instagram' => $path . '/instagram.png',
            'linkedin' => $path . '/linkedin.png',
            'logo' => $path . '/logo.png',
            'phone' => $path . '/phone.png',
            'pinterest' => $path . '/pinterest.png',
            'twitter' => $path . '/twitter.png',
            'email' => $path . '/mail.png'
        );

        if ( isset($this->config->additionalImages)) {
            foreach($this->config->additionalImages as $key => $value) {
                $baseMailData[$key] = $value;
            }
        }

        $this->images = $baseMailData;
        //Default template is based on class that called it
        $this->template = $this->template ?? explode("\\", get_class($this))[2];

    }
}