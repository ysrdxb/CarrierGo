<x-mail::message>
# Welcome to {{ $companyName }}!

Hello {{ $firstName }},

Thank you for registering with **{{ $companyName }}**! We're excited to have you on board.

To complete your registration and get started, please verify your email address by clicking the button below:

<x-mail::button :url="$verificationUrl">
Verify Email Address
</x-mail::button>

**Important**: This verification link will expire in **{{ $expiresInHours }} hours**. If you don't verify your email within this timeframe, you may need to register again.

## What's Next?

Once you verify your email:
- For **Free Plan**: Your account will be automatically set up and ready to use immediately
- For **Paid Plans**: Your registration will be reviewed by our team, and you'll receive an approval email shortly

If you didn't sign up for a {{ config('app.name') }} account, please ignore this email.

---

**Questions?** Contact our support team at support@{{ config('app.domain', 'carriergo.com') }}

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
