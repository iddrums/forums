@component('mail::message')
# Introduction

We need to confirm your email address. Cool!!

@component('mail::button', ['url' =>  url('/register/confirm?token=' . $user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
