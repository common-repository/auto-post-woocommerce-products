Auto Post WooCommerce Products
Contributors: carllockett3, freemius
Donate link: http://www.cilcreations.com/
Tags: twitter, woocommerce, product management, schedule, cron, social media, advertise products, facebook
Requires at least: 5.0
Tested up to: 5.5.1
Stable tag: 2.1.60
Requires PHP at least: 7.0 Recommended 7.2
WC requires at least: 3.3.0
WC tested up to: 4.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce product management and product posting to social media. APWCP is a powerful tool to assist you in managing your WooCommerce inventory and advertising products on social media.

== Description ==
This plugin will enable you to automatically (*Twitter*), or manually (*Facebook, Pinterest and Tumblr*) send a *WooCommerce* product as a post to your social media account. The post will include the product's title description, product's url and your hashtags. The image will automatically be pulled from the product's open graph data on it's product page.

The product's long url will be shortened with *Bitly* (*you will need a Bitly API generic user code, instructions included*).

You will also need *Twitter* API Codes to allow auto posting (*Twitter only*). You can acquire these by following the instructions in the help section of the plugin.

A valid SSL certificate must be installed on your website to post your products to your social media account(s).

 == Installation ==

*This section describes how to install the plugin and get it working.*

1. Install from the WordPress plugin repository. (*recommend method*)
2. Or download the zip file from WordPress and upload to your plugins directory.
3. Activate the plugin through the **Plugins** page in WordPress.
4. Follow the Quick Start page to get up and running!
5. Enter your *Twitter and Bitly API* information on the **Twitter** and **Settings** pages.
6. Select the categories of products for auto posting to Twitter and set your schedule.

= Localization =

* English (default) - always included.
* Spanish es_ES - included.
* French fr_FR - included.
* German de_DE - included.
* Portuguese pt_PT - included.
* .pot file (<code>auto-post-woocommerce-products.pot</code>) for translators is included.

= Credits =

* Using **Twitter for PHP - library** v3.6 by David Grudl [https://davidgrudl.com](https://davidgrudl.com).
* Using **Twitter OAuth.php** by Andy Smith
* Using **Freemius** to handle installations of the premium versions. [http://freemius.com](http://freemius.com)

== Frequently Asked Questions ==

= Why is the title of my product being truncated? =

> When your product title is shortened, you will see two periods at the end; **".."** which signifies it has been truncated. This process is due to either the length of your title, the length of your hashtags or a combination of both. *Twitter* posts must not exceed 280 characters, so to keep within that amount we trim some off the title only to make it fit.

=  How can my language translation be included? =

> There are a few options to have this plugin translated into your language. The first option is to use the .pot file in the languages folder and translate the phrases yourself. The second option is to utilize [https://translate.wordpress.org/projects/wp-plugins/auto-post-woocommerce-products]. The third option is to contact me to have your language included.

== Screenshots ==

1. Twitter/Bitly API code settings.
2. Settings page.
3. WooCommerce Category Options.
4. Product listing and management.
5. Product listing icon legend
6. Product quick edit.
8. Last auto posting information.
9. Click Analytics (PRO)
10. Manual Product Sharing
11. Status (debug) tab
12. APWCP Dashboard Widget

== Changelog ==
= 2.5.60 =
* 09-June-2020
* Corrected the issue of failing to activate plugin.
* minor bug fixes.

= 2.5.55 =
* 06-June-2020
* minor bug fixes.

= 2.5.53 =
* 06-June-2020
* minor bug fixes.

= 2.5.52 =
* 02-May-2020
* minor bug fixes.

= 2.1.51 =
* 09-FEB-2020
* Minor fixes and enhancements.

= 2.1.51 =
* 09-NOV-2019
* Fixed the sorting of the ALL TIME CLICKS column on the STATS tab.
* Fixed issue on Product List tab - selecting "Hide Variations" displayed no results.
* Bugs fixed as well.

= 2.1.49 =
* 16-SEP-2019
* Adjusted the enable WP_DEBUG_DISPLAY to be active only if WP_DEBUG is enabled.
* Fixed issue where the "Current Auto Posting Data" on the Status tab was showing incorrect totals.
* Fixed issue when selecting VIEW ALL PRODUCTS on the Product List tab no products were displayed.

= 2.1.48 =
* 16-SEP-2019
* Many fixes including to the Twitter auto posting function.

= 2.1.4.60 =
* 05-SEP-2019
* Corrected issue of the auto post posted list not being reset when the products in cue list is.
* On the STATISTICS tab (PRO), the clicks per week and per month were displaying incorrect data.
* Added HELP tab to the Statistics Tab.

= 2.1.4.50 =
* 24-AUG-2019
* Corrected Twitter auto posting issue.
* Updated to use new Bitly API v4 and corrected previous Bitly errors.
* Fixed issue with "sharing buttons" not showing on the individual sharing page.
* Pinterest should now show the sale price when product is on sale.
* Moved the RESET AUTO POST PRODUCTS box from the categories tab to the STATUS tab.

= 2.1.4.3 =
* 05-AUG-2019
* Corrected issue for the error "Call to a member function get_category_ids() on boolean".
* Corrected issue for Auto post list refreshing before all auto post products have been posted to Twitter.

= 2.1.4.2 =
* 17-JUL-2019
* Corrected issue for posts on Twitter showing as url encoded.
* Corrected a few other minor issues.

= 2.1.4.1 =
* 12-JUL-2019
* Added image to the next auto post item on the schedule tab.
* Corrected a couple of minor issues.

= 2.1.4 =
* 11-JUL-2019
* Added HELP tabs to the Categories, Product List and Schedule screens.
* Fixed issue with saving Bitly link on the Quick Edit page. You can now delete the link and save to reset or get a new short link.
* Fixed trash count after adding variable product with it's variations.
* New Feature! You are now able to set a product as "DISCONTINUED". Info for this is on the HELP tab of the Product list page.
* Fixed the schedule button for "EVERY 3 HOURS", This was trying to set a schedule every "23 hours".
* Added a button on the STATUS tab to reset portions of this plugin if you have issues.
* The next product to be auto posted will now be shown on the Schedule Tab.

= 2.1.3.1 =
* 12-MAY-2019
* Combined the SETTINGS and TWITTER tabs.
* Made a few changes to the Product list tab including showing the product's margin when cost figures are entered.
* Removed some of the system settings from the Status Tab and renamed others for clarity. The removed items are available in WordPress 5.2 under Tools->Site Health
* Added bookmark links on the Settings, Quick Start and Status tabs.
* The low stock setting has been moved to the Settings tab.

= 2.1.3.0 =
* 18-APR-2019
* Moved the SETTINGS and TWITTER tabs to the far right of the display.
* Added ability to change the primary product image with one of the images attached to the product. (Quick edit)
* Made a few changes to the Product list tab.
* Added a Product Cost box to the Quick edit and Bulk edit screens. More cost entries, such as shipping, will be added soon.
* Those using the plugin **Booster for WooCommerce** by Algoritmika Ltd, your cost figures will be imported.

= 2.1.3.0 =
* 06-APR-2019
* Fixed the STOCK MANAGEMENT section of Quick Edit. STOCK STATUS and BACKORDER STATUS were not displaying as they should depending upon the stock management selection.
* Instructions to auto post your product TWEETS from Twitter to FACEBOOK, TUMBLR, LINKEDIN and PINTEREST are now located in the Knowledge base on our website.
* Reworked the Quick Edit page code to fix a few issues with saving data and to reduce database calls.
* Resolved an issue with auto posting. Once the number of products to Tweet was down to one item, the function to reload the array was not getting called. This caused the program to always show the last posted product on the Schedule page.
* Removed checking for product issues. WooCommerce now controls product inventory management and will automatically adjust the inventory status based upon your other settings. Also, each product type only allows certain product management settings and the others are not shown to prevent any issues.
* Added product count to categories list.

= 2.1.2.9 =
* 13-MAR-2019
* Corrected issue with saving changes on the Quick Edit page. Warning was given about Bitly link that was incorrect and prevented saving any changes.

= 2.1.2.8 =
* 12-MAR-2019
* Corrected issue with the open graph tags (og tags) for product page titles, not correctly displaying if item is on sale.
* Adjusted the fields on the quick edit page and corrected the ones that were not showing.
* Corrected issue with currency conversion, showing incorrect currency symbol in some languages.
* When setting a sale price in quick edit, if you entered a price into the SALE PRICE box and did not use the CALCULATE sale price, the price was not saved.
* On the quick edit page, you are now able to add a new category and set it for the product you are editing.

= 2.1.2.7 =
* 04-MAR-2019
* Bulk edit has been revised.
* Corrected the link to submit a feature request or idea for our plugin. This link now points to new pages on our website.
* Updated all translations. If you find any error(s) in the translations of our plugin, please submit a support ticket.
* Corrected the issue where you could not select the same schedule as previously selected after pausing the schedule.

= 2.1.2.6 =
* 01-MAR-2019
* Update Freemius SDK

= 2.1.2.5 =
* 20-FEB-2019
* A number of small issues have been corrected.
* Improvements to reduce database calls and increase page load speed, especially with large product inventories.
* These improvements are needed for the new features that are being implemented.
* Removed the *Google+* sharing button.

= 2.1.2.4 =
* 27-DEC-2018
* Added button to manually reset the products to Tweet list.
* Fixed a few other issues including retrieving Bitly short links which had stopped working.

= 2.1.2.3 =
* 19-DEC-2018
* Added Bulk Actions to the Product list tab. The ability to edit products with bulk edit is currently being added to the plugin.
* Fixed issue when changing the auto post schedule, the screen would refresh over and over.
* Fixed the issues with the auto poster; showing incorrect number of products, re-posting the same product right after it was posted, resetting the products to post list when it was not time.

= 2.1.2.2 =
* 30-NOV-2018
* Added a few more new filters to the product list tab.
* Re-designed the Stats list page. You are now able to search as on the Product list tab.
* Fixed a few persistent bugs.

= 2.1.2.1 =
* 27-NOV-2018
* Added a new filter to the product list tab. Under Product Type you are now able to sort by Featured products.
* For social media sharing, manual or automatic, the product short description will be used as it has been. If you do not have a short description, one will be created for you from the product description. You can still edit the short description on the product edit page or the quick edit page (PRO feature).
* Corrected issue with the CRON scheduler. After pausing/disabling the auto posting schedule, it was taking too long to get confirmation from WP CRON when you set a new schedule.
* Added support for Persistent Cache.
* Removed the click data from the Product List tab to allow for better response. The setting to add or remove the product from the statistics tab is still available in the ID column.

= 2.1.2 =
* 20-NOV-2018
* Added a new filter to the product list tab. Under Product Type you are now able to sort by Featured products.
* For social media sharing, manual or automatic, the product short description will be used as it has been. If you do not have a short description, one will be created for you from the product description. You can still edit the short description on the product edit page or the quick edit page (PRO feature).
* Corrected issue with the CRON scheduler. After pausing/disabling the auto posting schedule, it was taking too long to get confirmation from WP CRON when you set a new schedule.
* Added support for Object Cache.
* Removed the click data from the Product List tab to allow for better response. The setting to add or remove the product from the statistics tab is still available under the ID column.

= 2.1.1.6 =
* 09-NOV-2018
* Bug fix: The automatic refreshing of the auto posting product list was not updating correctly and always showing the same number of products even after adding/removing categories.

= 2.1.1.5 =
* 03-NOV-2018
* Fixed the issue of the auto post products list refreshing before all products have been posted.
* Portuguese language translation is now included in our plugin! We now include English (default en_US), Spanish (es_ES), German (de_DE), French (fr_FR) and now Portuguese (pt_PT).
* Added clicks for last 30 days to stats.

= 2.1.1.4 =
* 20-OCT-2018
* You will no longer be asked to *Refresh the Products to post list* when you change categories or add/remove products from your inventory. This is now done automatically.
* Added *On sale* column to the Stats tab clicks product listing.
* Moved the setting for enabling Twitter Auto Posting to the Twitter Tab.

= 2.1.1.3 =
* 13-OCT-2018
* New layout for the total click data on the Stats tab.
* Added total revenue to the Sold column on the Product list tab.

= 2.1.1.2 =
* 06-OCT-2018
* Bitly stats were not updating - fixed.

= 2.1.1.01 =
* Fix issue with auto posting.

= 2.1.1 =
* 01-OCT-2018
* bug fix: Fixed issue with the RECENT click totals on the Stats tab. The figures for only the RECENT clicks were calculated incorrectly.
* bug fix: Time and dates were being displayed incorrectly in regards to product sales. This has been corrected.
* Changed the format of all dates displayed in the plugin. They are all uniform in the format of DD-MMM-YYYY. I have left the time alone using the default setting for time in Wordpress.

= 2.1.0 =
* 2018/09/26
* You can now search by product ID on the Product List tab.
* Made some enhancements to the Stats Tab.
* Added the ability to EDIT Bit.ly short links. This is on the Quick Edit Page.
* You can now change, per product, if that item is to be auto shared to Twitter. The feature is on the Product List tab in the ID column. (Thanks to Mike M. for this great idea!)

= 2.0.9 =
* 2018/09/18
* Added hashtags to the SHARING column of the PRODUCT LIST tab.
* Added the product image to the Quick Edit page.
* bug fix: Could not manually post to Pinterest, was receiving error "Too many redirects". Solved.
* Added button to manually post products to **Tumblr** on the *Sharing Page*.

= 2.0.8 =
* 2018/09/14
* In order to make it easier for our users to activate WP_DEBUG mode, there is now a new button on the STATUS tab that you can use to automatically set this in your WP_CONFIG.PHP file.
* bug fix: There was an issue on the SHARING page, the error "LOCALHOST and/or a non secure server, HTTP, cannot post to social media." was displaying incorrectly and preventing you from posting on this page.


= 2.0.7 =
* 2018/09/06
* bug fix: There was an issue with the Twitter tokens on the Twitter tab. Apparently some browser's auto fill of web forms would replace the access token with other data and if not caught that data was saved and not your token. This would cause your auto posts to fail. I have changed the type of input box used to try and prevent this in the future.
* **NOTE**: Please see the message on the Quick Start tab about your Twitter app. This will affect you if you do not follow the instructions contained there.
* Redesigned the Dashboard widget.
* Changed the stock quantity appearance on the product list tab. They now display much like in the new dashboard widget. Much easier to see low or out of stock quantities.

= 2.0.6 =
* 2018/08/28
* bug fix: When auto posting to Twitter the product's URL was occasionally not retrieved.
* bug fix: The uninstall option on the settings tab was always displaying as *unset*, even after setting it's value. The value that was stored in the database was correct.
* All PRO plans now can add product specific hashtags to each product!
* Added new posting schedule of **every 30 minutes** to the **Professional** plan. The **FREE**, **Starter** and **Business** plans now have one more posting schedule added!
* Changed the layout of the Last Twitter Post information box on the Schedule tab.
* bug fix: The message to *Refresh the products list* was not always displaying when it should.
* Changed the log display boxes on the **Status tab**. They will no longer wrap the text and the horizontal scroll bar will display.
* Did a little adjusting to the dashboard widget.

= 2.0.5 =
* 2018/08/15
* Removed the empty box showing on the Schedule tab when the schedule is disabled.
* Replaced many on the icons on the product list tab. The new icons are easier to understand.
* Corrected the *Next post* date and time. The time and date shown were incorrect, but the actual cron schedule was correct.
* **NEW** Added a new feature! Now you can click on the **featured star** in the product list to change it's setting.
* If you have a *Facebook App ID*, you can now include it on the settings tab. If you do not, the **CIL Creations** ID will be used.
* Added an option on the settings tab to enable/disable the APWP Dashboard widget.
* Auto Twitter postings will now skip any product that is **NOT** in stock *(See next item for description)*. These items will be shown in the APWP <code>error_log</code>
* Adjusted checking for *products out of stock*. Now all products that are listed as *out of stock*, regardless if the stock quantity is greater than zero, will be displayed.
* Fixed bug: auto posting to Twitter was not saving the last shared date.
* More product management features are planned to be released soon!

= 2.0.4 =
* 2018/08/02
* Updated the *Status tab* with additional information and better layout.
* Added support for the default WordPress admin themes. You have a new option on the OPTIONS tab to enable this.
* bug fix: The tabs were not showing the proper page when clicked due to coding error. Sorry! :)
* Changed the name of the WooCommerce Tab to Categories.
* minor fixes and enhancements.
* Added more features to the Quick Edit page! (PRO)
	* Cancel a sale for a product.
	* Now show if product is showing as 'On Sale'.
	* Set a product as featured.
	* Change the visibility of the product.
* Finally made the product list table column header sticky! Now the column labels will stay at the top of the screen as you scroll. To accomplish this, a few adjustment to the product listings were made:
	* Removed the Countries and Referrers columns. They are still on the Stats tab and the Clicks column remains.
	* Added a column for total units sold.
	* Combined the Sale Price and Discounts amount columns into one.
	* Added featured product 'STAR' to the ID column.
* Made many appearance changes so all elements will look alike throughout the plugin.


= 2.0.3 =
* 2018/07/16
* fixed bug: PHP 7.2 error; 'count must be array' was fixed.
* Added sortable columns.
* In the social media meta og tags, for the price of the product instead of using get_regular_price we are now using get_price which returns the current price which is the sale price if on sale.
* Also with the social media og tags, there was an error showing in the WordPress debug about not accessing post ID, sale price and regular directly. The "Doing it Wrong" error was corrected.
* Fixed bug: hashtags were not showing on auto Tweets.
* fixed bug: Changes to products utilizing the Quick Edit feature were not showing the updates on the front end right away. This has been fixed.
* NEW! Updated Product click stats page!
* fixed bug: The DELETE PERMANENTLY link to delete an individual product was fixed.
* Added security check to verify if user can delete posts/products.
* Updated Spanish translation.

= 2.0.2 =
* 2018/06/30
* Fixed bug on quick edit page: stock quantity would not save if zero & minimum value was set to zero, not allowing for negative stock.
* Added product list filter: Show any product not shared to social media in over 30 days.

= 2.0.1 =
* 06/28/2018
* Added more features and enhancements to the product listing. (trash item, view and empty trash, search) You can view many aspects of each product on one screen, such as, inventory stock level, backorder status, in stock/out of stock, regular price, sale price and current price, last shared date, product type and categories.
* A few bug fixes. (Issues with product list tab in the FREE version have been corrected.)
* Aligned the product stock status to match with WooCommerce. I adjusted the process for matching the stock status and managed stock status with backorder status.
* Added ability to hide the product listing legend.
* New Quick Edit feature! (pro versions)
* Included a couple features the were Pro into the FREE version!
* Added an enhanced sharing tab to share any product to social media.
* Fixed bug; font family style that affected the admin area.

= 2.0.0 =
* 06/03/2018
* Version 2.0.0! Many changes to the plugin! With the new Facebook policies, we are unable to automatically post the products. I have incorporated the Facebook Share button in the app to manually share any product in your inventory.
* Pinterest has changed some also, so it is incorporated into the plugin like Facebook is.
* Fixed bug in stats table; in the referrers column, the totals were not being reset before the next product in the list.
* Many updates and new features! Better inventory control features on the new Products List tab!
* Manually post a product to Google+

= 1.1.9 =
* 05/23/2018
* Added a PRODUCT LIST tab! This is in the wp-admin style and there are many more options for you products. The main new feature is the ability to post a product from your inventory to social media with a button click! More features will be coming soon. This new tab also has the Wordpress SCREEN OPTIONS at the top of the page for more settings.
* Removed the Product lists from the Woocommerce Tab.
* Changes are coming which will affect the ability to automatically post to FACEBOOK. If you are already able to post to Facebook, this will not change until August when Facebook will no longer allow the auto post feature in it's current form. If you are just installing APWP for the first time, Facebook will NOT allow you to create a new Facebook app that will allow posting as set in this plugin. Updates are in the works to allow this plugin to continue functioning as described for Facebook.

= 1.1.8 =
* 05/18/2018
* Enhanced debug tab - added a button to copy all the data to clipboard.
* Fixed bug in Stats tab where the total clicks shown at the bottom were incorrect.

= 1.1.7 =
* 05/16/2018
* Fix bug that was missed in v1.1.6

= 1.1.6 =
* 05/16/2018
* Added a tab for collecting debugging information.
* Updated Facebook SDK to latest version

= 1.1.5 =
* 05/10/2018
* Added new instructions for the Pinterest Access Token as Pinterest made some changes to their API.
* Removed a large portion of the help file included in the plugin. This information is now on our website.
* More small updates will be coming soon!

= 1.1.4 =
* 03/23/2018
* Minor fixes and enhancements.

= 1.1.3 =
* 03/16/2018
* Fixed the undefined index error for the Pinterest board and updated the error checking.
* Fix for the cycling refresh on the Schedule Tab page.

= 1.1.2 =
* 02/12/2018
* Fixed minor error showing in the WP error_log; **"calling get_woocommerce_product_list() arguments required is 1, none used."**
* Added status widget to WP Dashboard!
* Added thumbnail images to the product listings.
* Adjusted a few font/color settings.
* Fixed bug in the function to determine if at least one product category has been selected.
* Removed the info bar above the tabs and below the plugin name. Created a much cleaner look!
* Removed the FAQ section in the Help tab. There is a link that section on the website.
* Added Ajax functionality to some on the options.

= 1.1.1 =
* 01/17/2018
* Fixed the superscript notation above the Bitly links column for the Stats tab. Should be on Referrers column.
* Fixed issue with total clicks data not displaying at bottom of the stats tab.
* Corrected issue checking for LOCALHOST server. Bitly will not accept urls from localhost.
* Fixed bug in checking that all options are set for at least one social media account.
* Last post box on Schedule tab is now hidden when the schedule is paused.

= 1.1.0 =
* 01/12/2018
* Enhanced the appearance.
* Added support to clean up memory and decrease page load time.
* Plugin will now use the **Short Description** from your product pages in the media postings!
* Plugin can now add product meta data tags to your product pages for Twitter!
* PRO versions are now available!


= 1.0.3 =
* 11/27/2017
* Made a few changes to the layout to accommodate other languages.
* Spanish Translation (es_ES) now available.

= 1.0.2 =
* 11/22/2017
* Added css class to input checkbox; was missed and affects all admin pages.
* Added function to verify WooCommerce is active and installed; else disable plugin.
* The main tab to show is now the Schedule Tab.
* Removed the randomizing of the product's url to prepare for Bitly URL tracking data!
* Made adjustments to the product listings on the WooCommerce tab. Now only shows the parent products on variable items.

= 1.0.1 =
* 11/15/2017
* Added missing logo to the HELP tab.
* Repaired LICENSE and README files.
* Completed code setup for language translation.
* Made a few minor appearance changes.
* Added the ability to select all categories with a button click.

= 1.0 =
* Initial Release 11/08/2017


== Upgrade Notice ==
= 2.1.0 =
* Made some enhancements to the Stats Tab. You can now search by Product ID on the Product List tab.

= 2.0.0 =
* Version 2.0.0! Many changes to the plugin! With the new Facebook policies, we are unable to automatically post the products. I have incorporated the Facebook Share button in the app to manually share any product in your inventory. Many enhancements and fixes in the new update.

= 1.1.0 =
* Major Update to the GUI and new features added. A few issues also corrected.

= 1.0.3 =
* Added Spanish translation to the plugin, made a few changes in the appearance to accommodate other languages.

= 1.0.2 =
* Fixed the missing css class for the checkbox, added ability to verify WooCommerce is installed and active, set the SCHEDULE tab as the main tab displayed and removed the randomizing of the product links for new feature!

= 1.0.1 =
* Minor upgrade to correct a few issues.

= 1.0 =
* Initial Release 11/08/2017

== Plugin Features ==

* Ability to automatically post your *WooCommerce* products to *Twitter*.
* Ability to manually post your *WooCommerce* products to *Facebook*, *Pinterest*(PRO) and *Tumblr*.
* Post on a schedule by selecting one of the time frames.
* **Very easy setup!** Once setup is completed the plugin will run itself on your schedule.
* You choose which products are posted by selecting the categories the product is assigned to.
* The schedule can be paused at any time by just by selecting the option on the schedule tab.
* Includes an uninstaller to clean up all database entries used by this plugin.
* Product listing and management.
* Edit sale promotions.
* View the sale dates, click stats(PRO) and sale discount amounts within the listing page.
* View click stats(PRO) from Bitly.com; all clicks, referrers and the referrer countries.
* Enhanced quick edit screen to easily manage and update key items of your product.

== Privacy Policy ==

* Please view our **Privacy Policy** on our website. [https://www.cilcreations.com/apwp/site-policies/](https://www.cilcreations.com/apwp/site-policies/)

'<?php code(); // goes in backticks ?>'