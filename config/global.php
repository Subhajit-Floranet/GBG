<?php
return[
    'website_title'  	=> env('WEBSITE_TITLE','German Florist'),
    //'website_url'  	 	=> env('WEBSITE_URL','https://www.germanflorist.de'),
    //'website_url'  	 	=> env('WEBSITE_URL','http://phpstack-810541-2780260.cloudwaysapps.com/'),
    'website_url'       => env('WEBSITE_URL','https://www.germanflorist.de'),
    'website_tag_line'  => env('WEBSITE_TAG_LINE',''),
    'admin_email_id' 	=> env('ADMIN_EMAIL_ID','auto-update@germanflorist.de'),
    'support_email_id'  => env('SUPPORT_EMAIL_ID','support@germanflorist.de'),
    'currency'          => env('CURRENCY','USD'),
    'currency_html_code'=> env('CURRENCY_HTML_CODE','&#36;'),
    'currency_symbol'   => env('CURRENCY_SYMBOL','<i class="fas fa-dollar-sign"></i>'),
    'no_image' 			=> env('NO_IMAGE','/images/no_image.webp'),
    'no_image_thumb' 	=> env('NO_IMAGE_THUMB','/images/no_image_thumb.jpg'),
    'email_regards'     => env('email_regards','Team GermanFlorist.de (GF)'),
    'website_title_camel_case' => env('website_title_camel_case','GermanFlorist'),
    'facebook_link'     => env('FACEBOOK_LINK','https://www.facebook.com/germanflorist'),
    'twitter_link'      => env('TWITTER_LINK','https://twitter.com/germanflorist'),
    'pinterest_link'    => env('PINTEREST_LINK','https://in.pinterest.com/germanflorist/'),
    'youtube_link'      => env('YOUTUBE_LINK','https://www.youtube.com/channel/UCUV_VieOXg_DYSJJcoKzeuw'),
    'number_format_limit' => env('number_format_limit', '2')
];