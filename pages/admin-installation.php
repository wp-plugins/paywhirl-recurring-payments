<?php defined('ABSPATH') or die();


// Admin Orders page


?>
<div class="paywhirl-installation-page paywhirl-page flex-container">
	<header>
		<div class="center-contents a24 c10 d8">
			<div class="logo"></div>
		</div>
	</header>
	<div class="page-title">
		Installation
	</div>
	<div class="flex overflow">
		<div>
			<div class="intro">
				Attention: To complete setup please complete the following steps.
			</div>
			<div class="step">
				<div class="label">
					1.
				</div>
				<div class="details">
					Login into your Paywhirl account. (This can be done from within your Paywhirl Wordpress Plugin by clicking on "Paywhirl")
				</div>
				<div class="image-step-1"></div>
			</div>
			<div class="step">
				<div class="label">
					2.
				</div>
				<div class="details">
					Scroll down to the bottom and under the System Settings, click on Webhooks.
				</div>
				<div class="image-step-2"></div>
			</div>
			<div class="step">
				<div class="label">
					3.
				</div>
				<div class="details">
					Where it says "Register a new webhook" enter the following (replacing "your-website" with your website's address):
				</div>
				<div class="image-step-3"></div>
			</div>
			<div class="webhook">
				http://www.your-website.com/paywhirl-webhook
			</div>
			<div class="step">
				<div class="label">
					4.
				</div>
				<div class="details">
					Click Register Webhook
				</div>
			</div>
			<div class="step">
				<div class="label">
					5.
				</div>
				<div class="details">
					Kick back and relax because that was it!
				</div>
			</div>
		</div>
	</div>
</div>