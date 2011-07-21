=== Rezgo ===
Contributors: rezgo
Donate link: http://www.rezgo.com/
Tags: tours, activities, events, attractions, booking, reservation, ticketing, e-commerce, business, rezgo
Requires at least: 3.0.0
Tested up to: 3.1.0
Stable tag: 1.4

Connect WordPress to your Rezgo account and accept online bookings directly on your website.

== Description ==

**Rezgo** is a cloud based software as a service booking system that gives businesses the ability to manage their tour or activity inventory, manage reservations, and process credit card payments. This plugin is a full featured front-end booking engine that connects your WordPress site to your Rezgo account.

The Rezgo WordPress Booking Plugin is not an iframe or javascript widget based booking engine, it is a completely integrated booking interface that takes advantage of all the content management capabilities of WordPress.  Tag, search, tour list, and tour detail pages are all fully integrated with the WordPress site structure giving you the ability to link directly to product pages, specific dates, or apply promotional codes or referral ids.  Every Rezgo WordPress page is search optimized and index ready, which means your site gets all the benefit of your Rezgo content.

You get all the features of the regular Rezgo hosted booking engine plus the flexibility to completely control the look and feel of your customer booking experience.

= Plugin features include =

*	Complete control over look and feel through CSS and access to display templates
*	Powerful AJAX booking calendar features
*	Support for discount and referral codes
*	Fully search ready pages and search engine friendly URLs
*	Complete transaction processing on your own site (with secure certificate)*
*	Plus all the other features of Rezgo. (http://www.rezgo.com/features)
    
= Support for your Rezgo Account =

If you need help getting set-up, Rezgo support is only a click or phone call away:

* Rezgo Support Website (http://support.rezgo.com)
* Customer service foru] (http://getsatisfaction.com/rezgo)
* Rezgo on Twitter (http://www.twitter.com/rezgo)
* Rezgo on Facebook (http://www.facebook.com/rezgo.tour.operator.software)   
* Pick up the phone and call +1 (604) 983-0083
* Email support AT rezgo.com 
    
== Installation ==

= Install the Rezgo Booking Plugin =

1. Install the Rezgo Booking plugin in your WordPress admin by going to 'Plugins / Add New' and searching for *'Rezgo'* ** OR ** upload the `rezgo` folder to the `/wp-content/plugins/` directory
2. Activate the Rezgo plugin through the 'Plugins' menu in WordPress
3. Add your Rezgo account information in the plugin settings
4. Use the shortcode [rezgo_shortcode] in your page content
5. Or place `<?php do_action('rezgo_tpl_display'); ?>` in your templates
	
= Plugin Configuration and Settings =

1. Sign-up for a Rezgo account at http://www.rezgo.com
2. Setup your inventory and configure your account on Rezgo.
3. Set-up your payment methods in Settings > Payment Methods
4. Activate (Turn on) your Rezgo account (a credit card is required).
4. Make sure the Rezgo booking plugin is activated in WordPress.
5. Copy your Company Code and XML API from your Rezgo Settings.
6. If you would like to use the included Rezgo Contact Form, you may want to get a ReCaptcha API Key.
7. Create a Page and embed the Rezgo booking engine by using the shortcode: [rezgo_shortcode]

= Important Notes =

1. The Rezgo plug-in requires that you have permalinks enabled in your WordPress settings. You must use a permalink structure other than the default structure.  You can update your permalink structure by going to Settings > Permalinks in your WordPress admin.
2. If you DO NOT have a secure certificate enabled on your website, you should choose the option "Forward secure page to Rezgo".

== Frequently Asked Questions ==

= I'm getting a PHP error when displaying the tour page? =

This is most likely because you are using the default link structure in WordPress.  The Rezgo plug-in requires that you use permalinks in order to show the Rezgo content correctly.

= When I click on the book now button I get a page not found or server error? =

This is probably because you do not have a secure certificate installed correctly on your site.  If this is the case, or if you just don't know, we recommend you chose the "Forward secure page to Rezgo" option.

= Can I use the Rezgo WordPress Plugin without a Rezgo Account? =

No, the Rezgo WordPress Plugin needs to connect to your account via the Rezgo XML API.  Without your Rezgo credentials (specifically your Company Code (CID) and API Key) the Rezgo WordPress Plugin will not function.

= Can I change the look and feel of the booking engine? =

Yes, absolutely.  The default display templates are stored in the "default" folder.  You can either edit these templates or you can copy the folder and rename it.  Make sure to update your configuration settings with the new template folder name.  Note that updates to the plugin may overwrite changes you make to the default templates, so creating a new folder is the preferred option.

= Can I manage credit card payments on my WordPress site? =

Yes, the Rezgo WordPress plugin has the ability to handle credit card payments.  Make sure to configure your Rezgo account to connect to your payment gateway.  Rezgo supports a growing list of Global payment processors including Authorize.net, PayTrace, Chase Paymentech, Beanstream, Ogone, Eway, and many others.  In order for your site to handle payments, you will need to install a secure certificate.  Check with your web host if you need help installing a secure certificate.  If you do not wish to set-up a secure certificate, you can have the secure booking complete on your Rezgo white label booking engine.

= Will the plugin slow down my site? =

No, it's highly unlikely.  The Rezgo plugin is based on the same code as the Rezgo white label booking engine used by hundreds of tour and activity companies.  The plugin connects to the Rezgo XML API which has been optimized to deliver XML results.  Depending on where your WordPress website is hosted, there may be some delay in connecting to the Rezgo server.  If you are experiencing an unreasonable lag in responses, we recommend you consider hosting your website with BlueHost (http://www.tkqlhce.com/click-3178558-10376740).

= Can I add functionality to the Rezgo WordPress Plugin? =

Yes, you are free to add functionality to the display templates.  We recommend you create a new copy of the templates as updates to the plugin may overwrite any changes you make to the default templates.

== Screenshots ==

1. Your tours and activities will display in a list on your default tour page.  From the tour page, your customers will be able to search for available tours and activities by using the date search at the top of the page, searching your items using keywords, or browsing based on tags.
2. Detail pages are designed to provide your customers with all the information they need to make a booking decision.  They can view detailed information, browse your image gallery, or watch videos.  Visitors can also share your detail page on Twitter, Facebook, TripIt, Travelmuse, or Duffle.
3. When customers choose a date, they are presented with a list of options.  Customers can then choose a preferred option in order to continue the booking process.
4. Once a customer has chosen a date, they are returned to the details page with the price options available for the option selected.  Customers can enter a promotional code if available, enter the number of passengers or guests for each price level, and continue on to the secure booking page.  If the WordPress site is secure, the transaction will complete on the WordPress site.  If however, there is no secure certificate, the transaction will complete on your Rezgo hosted booking engine.

== Changelog ==

= 1.4 =
* Fixed an issue with the local include path being incorrect on some systems.
* Fixed some issues with pagination.
* Added some default configuration settings.
* Fixed an issue with the calendar not loading if there was no availability.
* Fixed a number of assorted minor issues.

= 1.3 =
* Initial release

== Upgrade Notice ==

= You have the most recent version =
