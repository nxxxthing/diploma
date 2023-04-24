<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Template</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <style>

    </style>
</head>
<body
    style="color: #333333; background-color: #ffffff; width: 100%; padding: 0; margin: 0 auto;">
<div style="font-family: 'SFProDisplay', Roboto, sans-serif; max-width: 600px; width: 100%; margin: 0 auto; font-size: 18px; line-height: 28px; color: #333333; background-color: #ffffff;">

    <div style="padding: 100px 20px 50px">
        <img style="display: block; max-width: 181px; width: 100%; height: auto; margin: 0 auto; object-fit: cover"
             src="" alt="Template"/>
        <p style="margin: 60px auto 0; text-align: center; max-width: 480px">
            {{__('site_labels.you_are_receiving_this_email')}}
        </p>
        <a href="{{$url}}"
           title="{{__('site_labels.reset_password')}}"
           target="_blank"
           style="font-family: 'SFProDisplay', Roboto, sans-serif; font-weight: bold; display: block; max-width: 165px; width: 100%; margin: 15px auto; padding: 13px 0; text-align: center; font-size: 14px; line-height: 16px; text-decoration: none;">
            {{__('site_labels.reset_password')}}
        </a>
        <p style="margin: 60px auto 0; text-align: center; max-width: 480px">
            {{__('site_labels.this_password_reset_link')}}
        </p>
        <p style="margin: 15px auto 0; text-align: center; max-width: 480px">
            {{__('site_labels.if_you_did_not_request_a_password_reset')}}
        </p>
        <p style="margin: 15px auto 0; text-align: center; max-width: 480px">
            {{__('site_labels.regard_platform_administration')}}
        </p>
    </div>

</div>

</body>
</html>
