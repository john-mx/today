<?php
namespace DigitalMx\jotr;
//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Utilities as U;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];

//END START

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Dev Process',
	]);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

//END START
?>
<h3>Organization</h3>
<ul>
<li>Project Directories
	<ul>
	<li>.git, .gitignore (vendor, docs, among others)
	<li>composer.json, vendor dir.  Guzzle, Plates, Monolog, Pimple
	<li>docs - various notes on project
	<li>config - Definitions, Local settings, Cache Settings, constants, initialize, passwords
	<li>var, var/ext - cache files.  All external are in var/ext to make it easy to symlink to other dirs on server
	<li>data - only used for file containing version
	<li>logs - monolog log files, rotate 5 days
	<li>plates - templates for all data.
	<li>public - public_html.  has css/, js/, docs/, fonts/ (has Frutiger, but not used), all public php/html pages
	<li>src - all Classes.  DisplayManager, CacheManager, Calendar, Camps.
	</ul>
<li>Local (on developer's machine)
	<ul>
	<li>path to root: /Users/john/Sites/

	<li>web site: jotr.local
	<li>access: no passwords
	<li>caches: private, cron updates
	<li>git remotes: dev, work, livex, live, hub (github).  Remotes link to .git directories below;
	</ul>
<li>Remotes (on remote server)
	<ul>
	<li>Path to git roots: /usr/home/digitalm/Sites/jtnp/
	<li>Each site has a root dir (all the files) and root.git directory (receives uploads and checks them out to the root dir).
	<li> Server's public html dir is ~/public_html. Each web url points to dir of same name in public_html. That dir is symlinked to "public" dir in the corresponding git root
	<li>on receiving repo, git checks out indicated branch to root.
	<li>Each site has its own set of caches, except for Livex, which shares the real site's external caches.

	<li>Dev Site: dev
		<ul>
		<li>root: jdev, jdev.git
		<li>default git branch: dev
		<li>public html:  jotrd.digitalmx.com -> root/public
		<li>access: one pw for all access
		<li>caches: all private, manual update
		</ul>
	<li>Beta Site: beta
		<ul>
		<li>root: jbeta, jbeta.git
		<li>default git branch: work
		<li>public html: jotrb.digitalmx.com -> root/public
		<li>access: simple pw for site.  Page controls same as live
		<li>caches: all private, manual update
		</ul>
	<li>Practice Site: jotrx
		<ul>
		<li>root: jotrx, jotrx.git
		<li>default git branch: live
		<li>public html: jotrx.digitalmx.com -> root/public
		<li>access: has its own set
		<li>caches: externals symlink to live, others private
		</ul>
	<li>Live Site: live
		<ul>
		<li>root: live, live.git
		<li>default git branch: live
		<li>public html: jotr.digitalmx.com -> root/public
		<li>access: public, but pw for admin functions
		<li>caches: cron update every 30 mins
		</ul>
	</ul>


<h3>Data/Version</h3>
<ul>
<li> is set to tag/date and added at commit

</ul>

<h3>Security</h3>
<ul>
	<li>Passwords are stored in config/passwords.ini
	<li>Each domain (dev, live) has its own set of passwords.
	<li> Passwords set a user level (0-9)
	<li> Each domain and page may require a minimum user level to access. If there is no listing for a page or domain in passwords.ini, there are no restrictions.
</ul>
