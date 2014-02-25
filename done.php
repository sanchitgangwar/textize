<html>
<head>
	<title>Done: Textize</title>

	<link rel="stylesheet" href="css/style_done.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.alerts.css">

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.alerts.js"></script>

	<script type="text/javascript">

		var prev_url = "";
		function updateSRC() 
		{
			var contents = document.getElementById('embed-area').value;
			var mediasrc = document.getElementById('mediasrc').value;

			if( prev_url == "" ) {
				contents = contents.replace('MEDIA_SRC', mediasrc);
			}
			else {
				contents = contents.replace(prev_url, mediasrc);
			}

			document.getElementById('embed-area').innerHTML = contents;
			jAlert('Media source updated to ' + mediasrc);
			prev_url = mediasrc;
		}
	</script>
</head>

<body>
	<div id="container">
		<div id="header">
			<span id="header-text-first-page">
				<a href="index.html" id="header_link">Textize</a>
			</span>
		</div>

		<div id="content">
			<div id="wrapper-1">
				<div id="embedfile">
					<textarea name="" id="embed-area" placeholder="Embed code here.."><?php

							// Select the file to open
							$file_name = "embed_video.html";
							if( isset( $_POST ) && $_POST['media_type'] == 'audio' ) {
								$file_name = "embed_audio.html";
							}

							$file_handle = fopen($file_name, 'r');
							$contents = fread($file_handle, filesize($file_name));
							fclose($file_handle);


							// Get page URL.
							$pageURL = 'http';
	 						if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 						$pageURL .= "://";
	 						if ($_SERVER["SERVER_PORT"] != "80") {
	  						$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	 						} else { $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; }

	 						$pageURL = str_replace('done.php', '', $pageURL);
	  						
	  						// Replace relative file references with absolute paths.
	  						$contents = str_replace('css/', $pageURL . 'css/', $contents);
	  						$contents = str_replace('js/', $pageURL . 'js/', $contents);
	  						$contents = str_replace('images/', $pageURL . 'images/', $contents);

	  						if( isset($_POST['text']) ) {
	  							$prev = '<textarea id="editor-area" placeholder="Text goes here.."></textarea>';
	  							$new = '&lt;textarea id="editor-area" placeholder="Text goes here..">' . $_POST['text'] . '&lt;/textarea>';
	  							$contents = str_replace($prev, $new, $contents);
	  						}
	  						
	  						// Using the less than entity
	  						$contents = str_replace('<', '&lt;', $contents);

	  						// Insert start and end time format.
	  						if ( isset( $_POST['end-time-format']) && isset( $_POST['start-time-format']) ) {
	 	 						$contents = str_replace('END_TIME_FORMAT', $_POST['end-time-format'], $contents);
	  							$contents = str_replace('START_TIME_FORMAT', $_POST['start-time-format'], $contents);
	  						}

	  						// If start-time-checkbox is unchecked, uncheck it in the template.
	  						if( isset($_POST['start-time']) && $_POST['start-time'] == 'No' ) {
	  							$prev = '<input type="checkbox" id="start-time-checkbox" class="checkbox" name="start-time" value="start-time" checked="checked"/>';
	  							$new = '<input type="checkbox" id="start-time-checkbox" class="checkbox" name="start-time" value="start-time"/>';
	  							$contents = str_replace($prev, $new, $contents);
	  						}

	  						// If end-time-checkbox is checked, check it in the template.
	  						if( isset($_POST['end-time']) && $_POST['end-time'] == 'Yes' ) {
	  							$prev = '<input type="checkbox" id="end-time-checkbox" class="checkbox" name="end-time" value="end-time">';
	  							$new = '<input type="checkbox" id="end-time-checkbox" class="checkbox" name="end-time" value="end-time" checked="checked">';
	  							$contents = str_replace($prev, $new, $contents);
	  						}

	  						// Convert relative font paths to absolutes.
	  						$contents = str_replace('fonts/', $pageURL . 'fonts/', $contents);

	  						// Dump the contents in the textbox.
	  						echo "$contents";
	 						?></textarea>
				</div>
				<div id="editor">
					<textarea id="editor-area" name="text" placeholder="Your transcript here.." 
						onclick="this.focus(); this.select();"><?php if ( isset( $_POST['text'] ) ) { echo $_POST['text']; } ?></textarea>
				</div>
			</div>
		</div>

		<div id="wrapper-2">

			<div id="embed-ins-area">
				Enter the audio/video URL to be inserted in the code:<br/>
				<input type="text" id="mediasrc" style="width: 50%"/>
				<button id="update" class="control-button" onClick="updateSRC()">Update</button><br/>
				<br/>

				Use the following code to embed in webpages:<br/>

				<textarea id="wrapper-code" onclick="this.focus(); this.select();" ><iframe src="url/to/file"></iframe></textarea>

			</div>

			<div id="transcript-ins-area">
				You can copy the above transcript and save it. The code on the left is to embed the similar 
				webpage in your pages.
			</div>
		</div>
	</div>
</body>
</html>		