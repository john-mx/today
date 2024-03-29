
<h3>How it works</h3>
<p>Today In The Park works by periodically (like every 1/2 hour) querying various web resources to get updated information on weather, alerts, air quality, etc.  This information is stored until the next update.  </p>
	When a web page is requested, the stored information is
	retrieved and compiled, and then sent a template.
	The template does the final formatting for displaying the page.
</p>
<p>The site is built using a language called "PHP", which is commonly used for web sites, including the nps.gov sites for parks. The web server is a common Linux server running Apache.</p>

<h3>How to set up the rotating display on a Samsung TV:</h3>
The system generates is a web page that displays
rotating "slides" on a tv screen.  Here's how to use it.
<ol>
<li>Turn on the TV and press Source on remote or press Home.  Press  down and right arrow on the remote (may be circle around central button) to move across sources and get to the “Internet” choice, and select it.
<li> If you get a page with bookmarks that shows a "Rotate" site, choose it from the bookmarks, and you're basically done.
<li> Otherwise
	<ol type='a'>
<li>Move to the URL field and press Enter.  You'll get a keyboard on the screen.
<li>Type this url into the url field using the arrow keys and middle “Enter” button to navigate the keyboard. <br /> <code>https://jotr.digitalmx.com/rotate.php</code><br />
<li> Scroll to and press the “Done” key when finished.  The rotating Today page should come up.
<li> Move the pointer to the Star at the right of the address bar and press Enter to bookmark the site.<br />(Choose local, I think).

<li>(One time setup) Use the arrow keys to navigate to the 3-bar menu pattern at very top right ,and choose Settings.  Under General, set the home page to Bookmarks, and Enable the “hide menu bar automatically” choice.
</ol>
<li> Just to be sure... At the top right (use arrow keys to scroll up until menu bar appears), make sure you are set for “100%”. Choose plus and minus tabs to change.
</ol>
<p>The screen will change every 15 seconds.  (The first iteration may take longer.) It's just one page, broken into sections that sequentially are visible on the screen.  The page updates every 15 minutes to pick up any new information.<br />
If something stops working, try moving the pointer to the top and press the "Refresh" button next to the address bar.
</p>
<h3>Set up the pages on an Amazon Firestick</h3>
<p>Amazon Firestick is an inexpensive device that looks like a thumb drive that can be plugged into the HDMI port of any TV, equiping it with a modern web browser and streaming services.</p>
<p>The Firestick needs some setup: you have to download Silk web browser and make a hidden developer change to keep it from going to sleep and set some bookmarks. (Contact me.) </p>
<p>Once set up, it's pretty simple:
<ul>
<li>Press the On button on the remote.  It should turn on your TV and start the web browser.
<li>If it doesn't go to the right page, press the Menu icon on the top of the page, and choose the "rotate" page from the bookmarks.
<li>To remove the top menu bar, press the menu button (3 bars) on the remote twice.
<li>Localize the display as below.
</ul>

<h3>Localization</h3>
There are a few settings that can be made locally on the display device you're using (TV, for example.)  You access the local settings by clicking on the "Now" time at the left side of the title bar on the rotating or scrolling TV display.
<ol>
<li>You can choose the name of the site, which will display in the title bar like "Welcome to the Cottonwood Visitor Center."
<li>You can choose which of the available pages appears in the rotation sequence on your device.
<li> You can choose the rotation rate: how many seconds per slide.
</ol>
These settings are stored locally on the display device for 48 hours after last usage, and then revert to default system settings.
<h3>Printing Today Pages</h3>
<p>
The page "today.php" can be printed from a web browser, and appropriate pages posted in a Visitor Center.
</p>
 <p>
 Printing will <ul>
 <li>change the margins to 0.5" on 8.5x11 pages
 <li> reduce the type size,
 <li> remove the "Current temperature" entry.
 </ul>
It should fit on five pages, each with room for expanding content. (today and alerts on page 1;  weather on page 2,  calendar on page 3; campgrounds on page 4; fees on page 5). Campgrounds page doesn't include site availability, so is fairly static.
</p>
<p>If printed to a PDF, then the pdf can easily be distributed electronically</p>

<h3>Information Sources</h3>
<p>Weather information comes from both weather.gov (NOAA) and weatherapi.com (commercial site).  The NOAA data is focused better on a geographic area, so it forecasts for Jumbo Rocks, not just 29 palms.  The weatherapi data is substituted if weather.gov fails, but the data is for 29 Palms and Indio, not Jumbo rocks and Cottonwood.</p>
<p>Current data comes from the sensors at Lost Horse Ranger Station, updated every few hours.  If data is not available, it is reported as "n/a". </p>
<p>Astro data (sun, moon) is from weatherapi.com. Should be non-controversial.</p>
<p>Air quality is from airnow.com (EPS site).</p>
<p>Fire Danger is entered manually by rangers.</p>
<p>Alerts are entered manually, by rangers, but informed by weather.gov alerts for Joshua Tree National Park.</p>
<p>Notices and Advice are entered manually by rangers</p>
<p>The Calendar is manually maintained by local rangers. This makes it possible to quickly add or remove events.</p>
<p>Campground status (reserved, closed) is manually entered by rangers. Available sites in First Come First Served campgrounds is manually entered.  The available campsite data for reserved sites comes from recreation.gov, updated every 30 minutes. The age of data is indicated by tags next to the number, so old data can be discounted. Only the Rotate page shows availability.</p>
<p>The web pages all used NPS Frutiger font early on, but it is only licensed for use on NPS apps, so the font was switched to Google Rubik. </p>

<h3>Scaling</h3>
<p>TV pages are much wider than they are long (16:9 ratio).  The length of the content on the page is not entirely predictable, as there are lists of notices on some pages, and weather information can take more words.</p>
<p>To make sure all information is visible on the screen, each page is scaled so the content length fits within the available screen space.  This will show up at the very bottom of the page as the % scaling applied to the page.  If pages are too compressed, it may be necessary to remove some items for the page.</p>
<h3>Help</h3>
<p>If you want to change any of the information, or if you have any problems or comments, contact John Springer. Phone or text to (503)329-7909 or email john@digitalmx.com.</p>
