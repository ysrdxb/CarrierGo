<x-mail::message>
# Your Account Has Been Approved!

Hello {{ $firstName }},

Great news! Your registration for **{{ $companyName }}** has been approved by our team.

Your account is now ready to use. Click the button below to log in and get started:

<x-mail::button :url="$loginUrl">
Log In to Your Account
</x-mail::button>

## What to do next:

1. Log in with your email and password
2. Complete your profile with your company details
3. Invite team members to start collaborating
4. Set up integrations to connect your shipping carriers

## Need help?

If you have any questions, contact support@{{ config("app.domain", "carriergo.com") }}.

Thanks,<br>
{{ config("app.name") }} Team
</x-mail::message>
