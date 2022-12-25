<?php

$router->get('/', function() {
    return sprintf('Ok - %s', env('APP_ENV'));
});

$router->group(['middleware' => 'log.route'], function() use($router) {

    $router->get('/pmsxUpdateAvail', 'ChannelController@test');

    $router->post('/login', 'UserController@authenticate');
    $router->post('/register', 'UserController@registerUser');

    $router->get('/getRates', 'RatePlanController@getRateData');
    $router->get('/mountHealthCheck', 'RatePlanController@fileMountHealthCheck');
    $router->get('/resumeSplits', 'RatePlanController@resumeSplits');
    $router->get('/ghcRemoveRates', 'RatePlanController@ghcRemoveRates');
    $router->get('/trialJob', 'RatePlanController@trialJob');
    $router->get('/ghcRateModification', 'RatePlanController@ghcRateModification');

    $router->get('/getReviews', 'PropertyController@getGoogleReviews');

    $router->group(['prefix' => 'message'], function() use($router) {
        $router->get('/index', 'MessageController@messageIndex');
    });

    $router->group(['prefix' => 'channel/{channel}'], function() use($router) {
        $router->post('/productDetails', 'ChannelController@productDetails');
        $router->post('/updateARI', 'ChannelController@updateARI');
        $router->post('/retrieveAvailability', 'ChannelController@retrieveAvailability');
        $router->post('/retrieveRestrictions', 'ChannelController@retrieveRestrictions');
        $router->post('/retrieveBookings', 'ChannelController@retrieveBookings');
        $router->post('/retrieveRatePlanCode', 'ChannelController@retrieveRatePlanCode');
        $router->post('/retrieveRatePlan', 'ChannelController@retrieveRatePlan');
        // DEBUG
        $router->post('/reserve', 'ChannelController@reserve');
        $router->post('/test', 'ChannelController@test');

    });
    
    $router->group(['prefix' => 'export'], function() use($router) {
        $router->get('/google/properties', 'PropertyController@getPropertiesForGoogle');
        $router->get('/google/restrictions', 'ExportController@exportCTARestrictionsToGHC');
        $router->get('/ttss/properties', 'PropertyController@getPropertiesForTTSS');
        $router->get('/ttss/all', 'ExportController@exportTTSSToGHC');
        $router->get('/propertydata[/{propertyCode}]', 'ExportController@exportPropertyData');
        $router->get('/property/{propertyCode}', 'ExportController@exportProperty');
        $router->get('/availability/{propertyCode}', 'ExportController@exportAvailability');
        $router->get('/rates/{propertyCode}', 'ExportController@exportRates');
        $router->get('/restrictions/{propertyCode}', 'ExportController@exportRestrictions');
        $router->get('/ratesandrestrictions/{propertyCode}', 'ExportController@exportRatesAndRestrictions');
        
        $router->group(['prefix' => 'cmg'], function() use($router) {
            $router->post('/availability/{propertyCode}', 'ExportController@exportAvailabilityCMG');
            $router->post('/rates/{propertyCode}', 'ExportController@exportRatesCMG');
            $router->post('/restrictions/{propertyCode}', 'ExportController@exportRestrictionsCMG');
        });
    });

    $router->group(['prefix' => 'services'], function() use($router) {
        $router->post('/storeOffer', 'BookingController@storeBookingOffer');
        $router->post('/processBooking', 'BookingController@processBookingForCheckout');
        $router->post('/processDataTrans', 'BookingController@processDataTransSecureFields');
        $router->post('/chargeScheduledPayments', 'BookingController@chargeScheduledPayments');    
        $router->post('/paymentCallback', 'BookingController@handleEcommpayCallback');
        $router->post('/confirmPayment', 'BookingController@retrieveEcommpayCallback');
    });

    $router->group(['middleware' => 'auth'], function() use ($router) {

        $router->post('/log', 'LoggingController@log');
        $router->post('/alertLog', 'LoggingController@alertLog');

        $router->group(['prefix' => 'client'], function() use($router) {
            $router->get('/reviews', 'ClientController@getPropertyReviews');
            $router->get('/rates', 'ClientController@getExchangeRates');
            $router->get('/properties', 'ClientController@findProperties');

            $router->get('/destinations', 'ClientController@getDestinations');
            $router->get('/destinations/summary', 'ClientController@getCountriesAndPropertyCount');
            $router->get('/configuration', 'ClientController@getConfiguration');
            $router->get('/content/{slug}', 'ClientController@getContent');
            $router->get('/all', 'ClientController@getClients');
            $router->get('/carousel', 'ClientController@getCarousel');
            $router->post('/newsletter', 'ClientController@subscribeToNewsletter');
            $router->post('/userQuery', 'ClientController@createUserQuery');           
        });

        $router->get('/countries', 'PropertyController@getCountries');

        $router->group(['prefix' => 'checkout'], function() use($router) {
            $router->post('/', 'CheckoutController@checkout');
            $router->post('/enquiry', 'CheckoutController@checkoutEnquiry');
        });

        $router->group(['prefix' => 'booking'], function() use($router) {
            $router->get('/{uid}', 'BookingController@getBooking');
        });

        $router->group(['prefix' => 'properties'], function() use($router) {
            $router->get('/', 'PropertyController@getProperties');
            $router->get('/{slug}', 'PropertyController@getProperty');
            $router->get('/{slug}/images', 'PropertyController@getPropertyImages');
            $router->get('/{slug}/offers', 'PropertyController@getPropertyOffers');
            $router->get('/{slug}/reviews', 'PropertyController@getPropertyreviews');           
            $router->post('/updateSource', 'PropertyController@updateSource');
        });

        $router->group(['prefix' => 'destinations'], function() use($router) {
            $router->get('/properties/{slug}', 'PropertyController@getPropertiesByDestination');
            $router->get('/offers', 'PropertyController@getPaginatedSpecialOffers');
            $router->post('/destinationOffers', 'PropertyController@getDestinationPropertyOffers');
            $router->get('/{slug}', 'PropertyController@getPaginatedPropertiesForDestination');
        });

        $router->group(['prefix' => 'service-bridge'], function() use($router) {
            $router->group(['prefix' => 'adchieve'], function() use($router) {
                $router->get('/properties', 'PropertyController@getCMGPropertyMeta');
                $router->get('/rooms', 'RoomController@getCMGRoomMeta');
                $router->get('/rateplans', 'RatePlanController@getCMGRatePlanMeta');
            });
        });
    });
});



