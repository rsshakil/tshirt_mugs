=== Simple PAY.JP Payment ===
Contributors: koyacode
Tags: payment,shopping,credit card,payment request,e-commerce
Requires at least: 4.9
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: 0.1.5
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
This plugin provides payment form by PAY.JP with simple shortcode.

Note:
The supported currency is only JPY so far.

Example of Shortcode:

	[simple-payjp-payment amount=50 form-id="id-string" name='no']

 * amount (mandatory): price in JPY
 * form-id (mandatory): any ID of the form
 * name: show/hide name field ('yes' => show (default), 'no' => hide)

You can confirm these information of each payments in descripton property of Charge record on PAY.JP admin panel.

Only one shoutcode can be placed in a page.


= Localization =
* English (default) - always included
* Japanese - always included

== Installation ==
1. Unpack the download package.
2. Upload all files to the /wp-content/plugins/ directory.
3. Activate this plugin in \"Plugin\" menu.

== Technical Details ==

How to use is summalized in the following page:
[WordPressプラグイン Simple PAY.JP Payment](https://it-soudan.com/simple-pay-jp-payment/)

== Changelog ==
= 0.1.5 =
Align settings order to that on PAY.JP API panel

= 0.1.4 =
Change redirect process

= 0.1.3 =
Add name field

= 0.1.2 =
Fix multiple post issue by reload

= 0.1.1 =
Fix readme.txt

= 0.1.0 =
Initial release
