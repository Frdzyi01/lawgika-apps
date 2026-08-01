<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'pancake' => [
        'page_access_token' => env('PANCAKE_PAGE_ACCESS_TOKEN'),
        'page_id'           => env('PANCAKE_PAGE_ID'),
        'base_url'          => env('PANCAKE_BASE_URL', 'https://pages.fm/api/public_api/v1'),
    ],

    'botcake' => [
        'api_url'      => env('BOTCAKE_API_URL', 'https://botcake.io/api/public_api/v1'),
        'access_token' => env('BOTCAKE_ACCESS_TOKEN'),
        'page_id'      => env('BOTCAKE_PAGE_ID', env('PANCAKE_PAGE_ID')),
        'templates'    => [
            'meeting_room_confirmation' => env('BOTCAKE_TEMPLATE_MEETING_ROOM_CONFIRMATION', '1732248697805244'),
            'meeting_room_checkout'     => env('BOTCAKE_TEMPLATE_MEETING_ROOM_CHECKOUT', '805177639284905'),
            'podcast_room_confirmation' => env('BOTCAKE_TEMPLATE_PODCAST_ROOM_CONFIRMATION', '1827038834946958'),
            'podcastroom_checkout'      => env('BOTCAKE_TEMPLATE_PODCASTROOM_CHECKOUT', '1039778505436096'),
            'podcast_room_checkout'     => env('BOTCAKE_TEMPLATE_PODCAST_ROOM_CHECKOUT', '1039778505436096'),
            'virtual_office_mail_notification' => env('BOTCAKE_TEMPLATE_VIRTUAL_OFFICE_MAIL_NOTIFICATION', '2856503864713589'),
            'virtual_office_guest_notification' => env('BOTCAKE_TEMPLATE_VIRTUAL_OFFICE_GUEST_NOTIFICATION', '1712545996642391'),
        ],
    ],

];
