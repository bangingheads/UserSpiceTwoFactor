# UserSpice Two Factor Authentication Plugin

This plugin allows you to have Two Factor Authentication signing into UserSpice. The plugin uses hooks added in 5.1.4, so it requires 5.1.4+.

Userspice can be downloaded from their [website](https://userspice.com/) or on [GitHub](https://github.com/mudmin/UserSpice5)

## Setting Up

1. Copy the two_factor plugin folder into /usersc/plugins/
2. Open UserSpice Admin Panel and install/activate plugin.
3. Configure plugin, enable Two Factor Authentication.
4. Add Two Factor to necessary accounts

## Activation

Once the plugin is enabled, a hook is added to every user's account.php to allow activation of Two Factor Authentication. This will be a button underneath the Edit Account button.

The user will be required to either enter the code given as their key, or scan the QR code to add the code to their app of choice.
Then it will be required to use a code from their device to verify it has been added successfully.

Once activated, every login will require a 2FA code from their mobile device.

## Deactivation

A user can disable Two Factor Authentication by the same way it was enabled. The account.php will have a Disable 2FA button that they can click, then confirm they would like to remove it from their account.


## Troubleshooting

The QR code generation requires the Imagick PHP plugin. If you do not have this installed you will manually have to enter the 2FA code on your device. More information on install Imagick can be found [here](https://www.php.net/manual/en/imagick.installation.php).


## Questions

Any issues? Feel free to open an issue on Github or make a Pull Request.

Need help? Add me on Discord: BangingHeads#0001.

Any help with UserSpice can be asked in their [Discord](https://discord.gg/j25FeHu)
