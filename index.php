<?php session_start();

if((isset($_SESSION['profile']) && $_SESSION['profile'] == false) && (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)){
	header('Location: profile.php');
	exit();
}
else{
}
?>

<?php include("header.php"); ?>
<h2><center>Arena Realty - Your Home Search Starts Here</center></h2> 
<div class="header">
	<img src="logo.jpg" width=100px; height=100px;>
	<h1>Arena Realty</h1>
	<p>Your Home Search Starts Here</p>  
</div>

<h2>About Us</h2>

<p>We are a local real estate agency passionate about helping people find their perfect home. With over 20 years of experience, we provide specialized knowledge on homes and neighborhoods in this area.</p>

<h2>Our Services</h2>

<div class="services">
	<div class="service">
		<h3>Buying</h3>  
		<p>We represent buyers to find and negotiate purchases of homes that match their needs and budget.</p>
	</div>

	<div class="service">
		<h3>Selling</h3>
		<p>We provide home valuations and market your home to qualified buyers, handling negotiations to sell for the highest price.</p>
	</div>

	<div class="service">
		<h3>Renting</h3>
		<p>We assist clients in finding suitable rental properties and provide guidance on market rates, listings, and the rental process.</p>    
	</div>
</div>

<h2>Why Choose Us?</h2> 

<p>We provide:</p>

<ul>
	<li>Industry-leading market knowledge</li>  
	<li>Highly personalized service</li>
	<li>Access to the latest MLS listings</li>  
	<li>Smooth handling of contracts and paperwork</li>
</ul>

<p>Contact us today to get started on your home search or sale!</p>
</div>
<?php include("footer.html"); ?>
