# Laravel mail

This is a distilled version of a commercial mailing system, files have been omitted for privacy/brevity.

## Flow

*Note: In the full system, a new mail would be sent within one of many controllers as opposed to via artisan command*

The mail flow can be represented as:

```php
app/Console/Commands/MailTestCommand::handle()

/* User provides 'id' within call, this is used create the static model instance required to generate the template as described in the command signature.  */ 
new app/Services/MailService

//Routes to appropriate mail template class
app/Services/MailService::send() 

/* Generates the required model instance using the given id based on the template and creates variables to be accessed during view rendering */
new app/Mail/[template]

//Builds appropriate mail template view with template variables
app/Mail/[template]::build() 

/* Templates reference component views in resources/views/components, which in turn call their respective component class except in the case of anonymous components */
resources/views/mail/templates/[template].blade.php
    app/View/Components/[component].php //passes component data to
    resources/views/components/[component].blade.php

//Return rendered html to MailService where it is send with the email given within the initial command call
```

## Styling and HTML structure

Due to mailing client compatibility issues, we lose the ability to use a significant amount of features. This means that tables are required for positioning, as well as certain suboptimal CSS rules, such as the declaration of media queries as internal CSS with !important. 

CSS styles are located within `resources/views/mail/html/themes` and are injected into the templates based on the respective classes `$theme` value, more details can be found [here](https://laravel.com/docs/8.x/mail#customizing-the-css)

## Improvements

Despite the difficulty of styling for mail templates, assuming it was a business priority, the styling system could be improved through the utilisation of SCSS, the reduction of CSS rules, or possibly the use of a CSS framework such as Bootstrap or Tailwind.

In the creation of model instances for mail template use there are some redundancies in the data used, as such we could optimise the Eloquent select queries to reduce the amount of load on the database if the respective template did not require certain subsets of data.