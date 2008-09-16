<h2>Installation</h2>
<p>For a simple installation, only 3 steps are required:</p>

<h3>Step 1: Write the XML</h3>
<p>This is the minimal XML layout:</p>
<pre class="source"><?php 
	echo htmlentities
	('<root version="0.1">
	<content>
		<name>Your game name</name>
		<portal_url>http://www.your-url.com/<portal_url>
	</content>
</root>');
?></pre>

<p>Copy this layout to a file and upload it to your webserver. Make sure this file is accessable from the web.</p>

<h3>Step 2: Security</h3>
<p>To prevent identity theft and phishing, we have added a security check: your portal_url website must contain an (invisible) link to your XML file.</p>

<p>Edit and add the following line to the head section of your portal site:</p>
<pre class="source"><?php echo htmlentities ('<link rel="browser-game-info" href="http://link-to-your-xml-file/info.xml">'); ?></pre>

<h3>Step 3: Validate and add your game</h3>
<p>Now add your game to our list by validating it. Our system will check if the XML is reachable and valid. Mind that you can validate your XML multiple times, but it will only be added once.</p>

<form method="post" onsubmit="alert ('Online soon... please hang on.'); return false;">
	<fieldset>
		<legend>Information XML Validator</legend>
		<label>Information XML URL</label>
		<input type="text" name="infourl">
		<button type="submit">Validate</button>
	</fieldset>
</form>

<h2>Advanced implementation</h2>
<p>This is just the beginning! If you were able to validate your simple XML, you can add way more information to your XML. Take a look at <a href="http://master.dolumar.be/serverlist/list/">this example</a> or go straight to the <a href="http://wiki.dolumar.be/index.php/Browser_Games_Hub">advanced documentation</a>.</p>
