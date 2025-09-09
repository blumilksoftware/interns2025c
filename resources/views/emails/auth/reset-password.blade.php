<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style type="text/css">
        {!! file_get_contents(resource_path('views/vendor/mail/html/themes/mail.css')) !!}
    </style>
</head>
<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="header">
                            <a href="{{ config('app.url') }}" style="display: inline-block;">
                                <img src="{{ asset('Images/Logo/Logo300x300.png') }}" alt="{{ config('app.name') }}" class="logo" style="max-height: 120px; max-width: 120px;">
                            </a>
                            <br>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="body" width="570" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell">
                                        <div class="content">
                                            <h1>{{ __('email.auth.reset_password.title') }}</h1>
                                            
                                            <p>{{ __('email.auth.reset_password.greeting', ['name' => $user->name ?? __('email.auth.reset_password.default_greeting')]) }}</p>
                                            
                                            <p>{{ __('email.auth.reset_password.message') }}</p>
                                            
                                            <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td align="center">
                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                                                            <tr>
                                                                <td align="center">
                                                                    <a href="{{ $resetUrl }}" class="primary-button" target="_blank" rel="noopener">{{ __('email.auth.reset_password.button') }}</a>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            
                                            <p>{{ __('email.auth.reset_password.expiration', ['count' => config('auth.passwords.users.expire')]) }}</p>
                                            
                                            <div class="panel">
                                                <div class="panel-content">
                                                    <div class="panel-item">
                                                        <p><strong>{{ __('email.auth.reset_password.security_notice') }}</strong></p>
                                                        <p>{{ __('email.auth.reset_password.security_message') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <p>{{ __('email.best_regards') }},<br>
                                            {{ __('email.team') }} {{ config('app.name') }}</p>
                                        </div>
                                        
                                        <div class="subcopy">
                                            <p>{{ __('email.auth.reset_password.manual_reset') }}</p>
                                            <p><a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
                                            <p>{{ __('email.auth.reset_password.ignore') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="content-cell" align="center">
                                        <p>© {{ date('Y') }} {{ config('app.name') }}. {{ __('email.auth.reset_password.ignore') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
