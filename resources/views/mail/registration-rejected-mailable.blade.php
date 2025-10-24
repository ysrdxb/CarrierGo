<x-mail::message>
# Registration Status Update

Hello {{ $firstName }},

Thank you for your interest in {{ config("app.name") }}. Unfortunately, we are unable to approve your registration for **{{ $companyName }}** at this time.

## Reason:

{{ $rejectionReason }}

## What you can do:

- **Review the feedback** provided above
- **Update your information** and resubmit your application
- **Contact us** if you have questions about the decision

Please feel free to contact our support team at support@{{ config("app.domain", "carriergo.com") }} if you would like to discuss this further or reapply with updated information.

We appreciate your understanding.

Thanks,<br>
{{ config("app.name") }} Team
</x-mail::message>
