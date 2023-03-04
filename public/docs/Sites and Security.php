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
<li> Web access: git root determines overall access

<li>Local (on developer machiine)
	<ul>
	<li>path to root: /Users/john/Sites/
	<li>git root: jotr
	<li>web site: jotr.local
	<li>access: no passwords
	<li>caches: private, cron updates
	<li>git remotes: dev, work, livex, live, hub (github).  Remotes link to .git directories below;
	</ul>
<li>Remote (on remote server)
	<ul>
	<li>Path to git roots: /usr/home/digitalm/Sites/jtnp/
	<li>Each site has a root dir (all the files) and root.git directory (receives uploads and checks them out to the root dir).
	<li> Server's public html dir is ~/public_html. Each web url points to dir of same name in public_html. That dir is symlinked to "public" dir in the corresponding git root
	<li>on receiving repo, git checks out indicated branch to root.
	<li>Each site has its own set of caches, except for Livex, which shares the real site's external caches.

	<li>Dev Site
		<ul>
		<li>root: jdev (branch dev)
		<li>public html:  jotrd.digitalmx.com
		<li>web site: jotrd.digitalmx.com
		<li>access: one pw for all access
		<li>caches: all private, manual update
		</ul>
	<li>Beta Site
		<ul>
		<li>root: jbeta (branch work)
		<li>public html: jotrb.digitalmx.com
		<li>web site: jotrb.digitalmx.com
		<li>access: simple pw for site.  Page controls same as live
		<li>caches: all private, manual update
		</ul>
	<li>Test Site
		<ul>
		<li>root: jotrx (branch live)
		<li>public html: jotrx.digitalmx.com
		<li>web site: jotrx.digitalmx.com
		<li>caches: externals symlink to live, others private
		</ul>
	<li>Live Site
		<ul>
		<li>root: jotr (branch live)
		<li>public html: jotr.digitalmx.com
		<li>web site: jotr.digitalmx.com
		<li>caches: cron update every 30 mins
		</ul>
	</ul>

<h3>Development Process</h3>
Branch live is always the code on remote live and jotrx
beta branched from live and is the main working copy
Feature branches are branched from beta
<ol>
<li><b>Develop</b><ol>
	<li>Development occurs on developers machine in repo work or  branches derived from work and merged back in.
	<li>push dev <dev branch>:dev
	</ol></li>
<li><b>Beta Test</b><ol>
	<li>switch work,
	<li> merge dev and commit
	<li> push beta (work branch)
	<li> make sure it works
	</ol></li>
<li><b>Live</b><ol>
	<li>switch to live,
	<li> merge --squash work
	<li> change tag
	<li>Commit -m "what's new" to set version/build in data/version
	<li> push live to live
	<li> push live to jotrx

	</ol></li>
</ol>

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
