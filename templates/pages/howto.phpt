<h2>Installation</h2>
<p>For a simple installation, only 3 steps are required:</p>

<h3>Step 1: Write the XML</h3>
<p>This is the minimal XML layout:</p>
<pre class="source"><?php 
	echo htmlentities
	('<browsergameshub version="0.1">
	<name>Your game name</name>
	<site_url>http://www.your-url.com/</site_url>
</browsergameshub>');
?></pre>

<p>Copy this layout to a file and upload it to your webserver. Make sure this file is accessable from the web.</p>

<h3>Step 2: Security</h3>
<p>To prevent identity theft and phishing, we have added a security check: your website, specified in the <?=htmlentities('<site_url>')?> tags, must contain an (invisible) link to your XML file.</p>

<p>Edit and add the following line to the head section of your portal site:</p>
<pre class="source"><?php echo htmlentities ('<link rel="browser-game-info" href="http://link-to-your-xml-file/info.xml" />'); ?></pre>

<h3>Step 3: Validate and add your game</h3>
<?php include ('validator/form.phpt'); ?>

<h2>Advanced implementation</h2>
<p>This is just the beginning! If you were able to validate your simple XML, you can add way more information to your XML. Take a look at the <a href="http://wiki.dolumar.be/index.php/Browser_Games_Hub">advanced documentation</a>.</p>

<p>Or go straight to the XML schemas:</p>
<ul>
	<li><a href="<?=BASE_URL?>schema/information.xsd">Information Schema</a></li>
	<li><a href="<?=BASE_URL?>schema/ranking.xsd">Ranking Schema</a></li>
</ul>
