=== Rezgo Online Booking ===
Contributors: rezgo
Donate link: http://www.rezgo.com/
Tags:  tour operator software, tour booking system, activity booking software, tours, activities, events, attractions, booking, reservation, ticketing, e-commerce, business, rezgo
Requires at least: 3.0.0
Tested up to: 4.2.2
Stable tag: 1.8.7

Sell your tours, activities, and events on your WordPress website using Rezgo.

== Description ==

> This plugin is completely free to use, but it requires a Rezgo account.  <a href="http://www.rezgo.com">Try Rezgo today</a> and experience the world's best hosted tour and activity booking platform.

**Rezgo** is a cloud based software as a service booking system that
gives tourism businesses the ability to manage their tour or activity
inventory, manage reservations, and process credit card payments. This
plugin is a full featured front-end booking engine that connects your
WordPress site to your Rezgo account.

= Don't settle for an iframe or javascript widget =

The Rezgo WordPress Booking Plugin is a completely integrated booking
engine that takes advantage of all the content management
capabilities of WordPress.  Tag, search, tour list, and tour detail
pages are all fully integrated with the WordPress site structure
giving you the ability to link directly to product pages, specific
dates, or apply promotional codes or referral ids.  Every Rezgo
WordPress page is search optimized and index ready, which means your
site gets all the benefit of your Rezgo content.

You get all the features of the regular Rezgo hosted booking engine
plus the flexibility to completely control the look and feel of your
customer booking experience.

= Plugin features include =

* Complete control over look and feel through CSS and access to display templates
* Full multiple booking (shopping cart) functionality
* Powerful AJAX booking calendar features
* Support for discount and referral codes
* Fully search-ready pages and search engine friendly URLs
* Integrated media gallery for photos and videos
* Complete transaction processing on your own site (with secure certificate)
* Full integration with 20+ payment systems including PayPal, Authorize.net, and many more.
* Plus all the other [features of Rezgo] (http://www.rezgo.com/features)

= Support for your Rezgo Account =

If you need help getting set-up, Rezgo support is only a click or
phone call away:

* [Rezgo Support](https://www.rezgo.com/support/)
* [Rezgo on Twitter](http://www.twitter.com/rezgo)
* [Rezgo on Facebook](http://www.facebook.com/rezgo)
* Pick up the phone and call +1 (604) 983-0083
* Email support AT rezgo.com

== Installation ==

= Install the Rezgo Booking Plugin =

1. Install the Rezgo Booking plugin in your WordPress admin by going
to 'Plugins / Add New' and searching for 'Rezgo' **OR** upload the
'rezgo-online-booking' folder to the `/wp-content/plugins/` directory
2. Activate the Rezgo plugin through the 'Plugins' menu in WordPress
3. Add your Rezgo Company Code (CID) and API KEY in the plugin settings
4. Use the shortcode [rezgo_shortcode] in your page content. Advanced shortcode commands are [available here](http://rezgo.me/wordpress).
5. Or place `<?php echo do_shortcode('[rezgo_shortcode]'); ?>` in your templates

= Plugin Configuration and Settings =

In order to use the Rezgo plugin, your Rezgo account must be activated.  This means that you **must** have a valid credit card on file with Rezgo before your Rezgo plugin can connect to your Rezgo account.

1. Make sure the Rezgo booking plugin is activated in WordPress.
2. Copy your Company Code and XML API KEY from your Rezgo Settings.
3. If you would like to use the included Rezgo Contact Form, you may
want to get a ReCaptcha API Key.
4. Create a Page and embed the Rezgo booking engine by using the
shortcode: [rezgo_shortcode]
5. Advanced shortcode commands are available here at http://rezgo.me/wordpress

= Important Notes =

1. The Rezgo plug-in requires that you have permalinks enabled in your
WordPress settings. You must use a permalink structure other than the
default structure.  You can update your permalink structure by going
to Settings > Permalinks in your WordPress admin.
2. The Rezgo plug-in is not supported on posts, it will only function on pages.
3. The Rezgo shortcode cannot be placed on a page that will be used as a static homepage.  The Rezgo shortcode must be placed on a page that has a slug.
4. If you DO NOT have a secure certificate enabled on your website,
you should choose the option "Forward secure page to Rezgo".

== Frequently Asked Questions ==

= Read this first: When you encounter a problem with the Rezgo WordPress plugin... =

... before you do anything else, first check your Rezgo white label site to make sure everything works there.  If your white label website is working correctly and all your information is updated, then there might be an issue with your specific WordPress install.  If so, refer to the following common scenarios: 

= I have added the shortcode to a page but Rezgo is not displaying =

The most common reason is a problem connecting to your Rezgo API. Try removing and replacing your CID and API Key in the WordPress Rezgo settings page.  Check to make sure that, if the API Key you created is IP restricted, that the IP address of your website has not changed.

= Why am I seeing PHP errors or PHP code when displaying the tours? =

There are certain server requirements for the plugin to operate correctly. In particular, the following PHP directives need to be set accordingly.  Your hosting provider can help you properly configure these directives.

* PHP's [short_open_tag](http://www.php.net/manual/en/ini.core.php#ini.short-open-tag) syntax must be ON
* The PHP [Client URL Library](http://www.php.net/manual/en/book.curl.php) must be installed
* PHP's [safe_mode](http://www.php.net/manual/en/features.safe-mode.php) needs to be OFF
* PHP's [open_basedir](http://www.php.net/manual/en/ini.core.php#ini.open-basedir) must be ON

= When I click on the details link I get a page not found error or nothing happens =

This could be because you are using the default link structure in WordPress. The Rezgo plug-in requires that you use permalinks in order to show the Rezgo content correctly.

= When I click on the book now button I get a page not found or server error? =

This could be because you do not have a secure certificate installed correctly on your site.  If this is the case, or if you are just not sure, we recommend you chose the "Forward secure page to Rezgo" option.

= Why are the tabs, calendar or image gallery not working? =

This could be due to a conflict between the JavaScript that the Rezgo plugin uses and that of your theme or another plugin. You can often resolve this by going to the Rezgo plugin settings and changing the template to "no-conflict".

= Can I use the Rezgo WordPress Plugin without connecting to Rezgo? =

The Rezgo WordPress Plugin needs to pull tour and activity data so it needs to connect to your account via the Rezgo XML API. Your Rezgo credentials (specifically your Company Code (CID) and API Key) are used by the Rezgo WordPress Plugin to display your tour and activities on your WordPress site.

= Can I manage credit card payments on my WordPress site? =

Yes, the Rezgo WordPress plugin has the ability to handle credit card
payments.  Make sure to configure your Rezgo account to connect to
your payment gateway.  Rezgo supports a growing list of Global payment
processors including Authorize.net, PayTrace, Chase Paymentech,
Beanstream, Ogone, Eway, and many others.  In order for your site to
handle payments, you will need to install a secure certificate.  Check
with your web host if you need help installing a secure certificate.
If you do not wish to set-up a secure certificate, you can have the
secure booking complete on your Rezgo hosted booking engine.

= I have updated my pricing or tour details, but my WordPress site doesn't show the new information =

Check to see if you are using a caching plugin like WPCache.  These caching plugins are great for speeding up your site but will also cache old details and pricing information.  You will want to exclude the Rezgo pages from your caching in order to avoid this in the future.

= I received an API warning email from Rezgo, what should I do? =

The Rezgo API automatically monitors usage and will notify you should your API show unusual activity.  If you receive an API warning message, check your web stats or analytics to see if you can find any unusual spikes in traffic.  If you are using a content delivery network (CDN) like CloudFlare or you do daily PCI scans from a service like McAfee, make sure to exclude your Rezgo pages.  If you continue to receive API warnings from Rezgo, this may be an indication that something is triggering excessive usage on your site.  If you do not rectify the problem, your API usage may be restricted.

= What if I have a problem not covered here? =

Not a problem, you can contact us directly and create a support ticket by emailing <support@rezgo.com>

== Screenshots ==

1. Once you activate the Rezgo WordPress plugin, you will need to enter 
in your Rezgo API credentials on the Rezgo settings page located in your 
WordPress Admin.  Look for Rezgo in the sidebar.
2. Your tours and activities will display in a list on your default
tour page.  From the tour page, your customers will be able to search
for available tours and activities by using the date search at the top
of the page, searching your items using keywords, or browsing based on
tags.
3. Detail pages are designed to provide your customers with all the
information they need to make a booking decision.  They can view
detailed information, browse your image gallery, or watch videos.
Visitors can also share your detail page on Twitter and Facebook.
4. When customers choose a date, they are presented with a list of
options.  Customers can then choose a preferred option in order to
continue the booking process.
5. Once a customer has chosen a date, they are returned to the details
page with the price options available for the option selected.
Customers can enter a promotional code if available, enter the number
of passengers or guests for each price level, and continue on to the
secure booking page.  
6. If the WordPress site is secure, the transaction
will complete on the WordPress site.  If however, there is no secure
certificate, the transaction will complete on your Rezgo hosted
booking engine.

== Changelog ==

= 1.8.7 =
* Security patch for PrettyPhoto JS library
* Re-ordered logic for fetching thumbnail images

= 1.8.6 =
* Various bug fixes

= 1.8.5 =
* Fixes to image thumbnails to support both new and old formats.
* Modified JS validation on PAX form

= 1.8.4 =
* Fixes to media gallery and map on details page.

= 1.8.3 =
* Minor bug fixes.

= 1.8.2 =
* Added new output method.
* Fixed an issue with SSL detection.

= 1.8 =
* Added support for new line items system.
* Improved AJAX booking request to better prevent accidental submission.
* Added no-conflict template to the update exceptions.
* Added new line items method to rezgo class.

= 1.7 =
* Brand new shopping cart interface allows many items to be booked at once.
* New XML commit request supporting multiple items.
* New anti-spam measures added to contact form.
* Inconsistent date labels have been changed to "booked for."
* Some share links removed from item details.
* Fixed a bug causing the search date range to produce inconsistent results.
* Many bug fixes and performance improvements.

= 1.6.1 =
* Fixed an issue with the new default template not loading in certain themes.
* Added a state/prov dropdown for countries with existing state/prov lists.
* Fixed a number of small display issues with the new payment template.
* Added a no-conflict template for themes with jQuery conflicts.
* Fixed a rare bug preventing the booking page from forwarding to Rezgo.

= 1.6 =
* Updated payment step of booking page to new async version.
* Updated calendar ajax to new faster version used on white label.
* Updated jQuery in default template to use noConflict() mode.
* Fixed a number of small issues with the checkout process.
* Fixed a bug preventing the calendar from going forward more than 12 months.

= 1.5 =
* Added support for passing variables to the shortcode.
* Added support for new multi-tag searches.
* Improved handling of API keys entered on settings page.
* Switched all remaining file fetching to use configured fetch method.
* Plugin update should no longer remove custom templates.
* Fixed a number of display and instruction errors on settings page.
* Fixed an issue with 'required' field alerts on some browsers.
* Fixed a rare bug with the receipt print button.
* Fixed a bug with smart/keyword searches failing due to bad encoding.
* Fixed an issue with the plugin not returning it's output correctly.

= 1.4.5 =
* Initial release.

== Upgrade Notice ==

= You have the most recent version =