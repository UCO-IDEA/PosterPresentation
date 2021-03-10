<html>
<head>
<?php 
	include("head.php");
?>

<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="css/main.css">

<style>
	html,body{
		height: 100%;
		margin: 0;
	}
	.wrapper{
		min-height: 100%;
		margin: 0 auto;
	}
</style>

</head>

<body>
	<div class="wrapper">
	<div class="container-fluid">
		<div class="container">
			<div class="row">
					<div class="logo_img ml-auto col-3">
						<img class="img-fluid" src="logo/Digital_Poster_Logo-Large.svg">
					</div>
					<div class="heading col-6 pt-5">
						<h1 class="primary-color">DIGITAL</br>POSTER</h1>
						<h4>An Interactive Poster Tool</h4>
					</div>
				</div>
		</div>
	</div>

	<div class="container-fluid" id="main-content">
		<div class="container">
			<div class="row">

				<div class="ml-auto col-3">
					<div class="card">
						<div class="card-header primary-bg">
							<h4>What you can do</h4>
						</div>
						<div class="card-body">
							<p>Give your poster</p>
							<ul>
								<li>Text</li>
								<li>Images</li>
								<li>Video</li>
							</ul>
						</div>
						<div class="card-footer">
							<p class="primary-color" >View Our Example</p>
							<a href="https://cece.uco.edu/idea/PosterPresentation/ShowPoster.php?PosterID=67" class="btn btn-primary text-bold full-width">Example</a>
						</div>
					</div>
				</div>

				<div class="col-3">
					<div class="card" style="margin-top: -20px">
						<div class="card-header primary-bg">
							<h4>Get Started</h4>
						</div>
						<div class="card-body" style="height: 190px;">
							<p>Do you have a poster image created and understand how to use Digital Poster?</p>
						</div>
						<div class="card-footer">
							<p class="primary-color">Start when you are ready</p>
							<a href="create.php" class="btn btn-primary text-bold full-width">Start</a>
						</div>
					</div>
				</div>

				<div class="col-3 mr-auto">
					<div class="card">
						<div class="card-header primary-bg">
							<h4>How to do it</h4>
						</div>
						<div class="card-body">
							<ul>
								<li>Double-click and add</li>
								<li>Change appearence</li>
								<li>Add content</li>
								<li>Post when complete</li>
							</ul>
						</div>
						<div class="card-footer">
							<p class="primary-color">View the full instructions</p>
							<a href="instructions/Digital Poster Instruction v2.pdf" target="_blank" class="btn btn-primary text-bold full-width">Instructions</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div> <!-- wrapper -->
	<div class="container-fluid">
		<div class="row">
			<?php include("footer.php"); ?>
		</div>
	</div>
</body>
</html>
