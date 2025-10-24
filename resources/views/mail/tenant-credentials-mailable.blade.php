<x-mail::message>
# Welcome to {{ $tenantName }}! 🎉

Hi {{ $firstName }},

Your account has been successfully created and is ready to use!

## Your Login Details

Please keep these credentials safe and secure. You can change your password after logging in.

**Email Address:**
```
{{ $email }}
```

**Temporary Password:**
```
{{ $password }}
```

**Domain:**
```
{{ $domain }}
```

<x-mail::button :url="$loginUrl">
Login to Your Account
</x-mail::button>

## Quick Tips

1. **Change Your Password** - Log in immediately and change your temporary password to something memorable
2. **Set Up Your Profile** - Update your profile information in the settings
3. **Invite Team Members** - Add other users to your account
4. **Read Documentation** - Check out our help guides for getting started

## Need Help?

If you have any questions or issues accessing your account, please don't hesitate to contact our support team.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
