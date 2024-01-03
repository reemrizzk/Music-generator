<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Musicgenerator: generate short music melodies!">
        <meta name="keywords" content="Music,song,generate,generator">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
        <link rel="icon" href="images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
        <link rel="stylesheet" href="css/main.css">
        <!--[if IE]>
            <style>
			    #ie-alert {
					display: block;
			    }
		    </style>  
        <![endif]-->
        <script src="js/jquery.min.js"></script>
        <title>Music Generator</title>
    </head>
    <body style="margin:16px;">
        <h1>Music Generator 1.3</h1>
        <p>A music generator written in PHP and Javascript. <a href="https://github.com/reemrizzk/Music-generator">View project on Github</a></p>
        <p>Use this music generator to generate a simple music melody, in both sound and notation forms. Customize the tempo, key signature, time signature, and more!</p>
        <?php require_once "generator.php"; ?>
    </body>
</html>
