<?php

//Inclusion of the WordPress file so we can use WP_Query.
if ( ! defined('ABSPATH') ) {
    /** Set up WordPress environment */
    require_once( '/public_html/wp-load.php' );
}

//PHPMailer files.
require_once('/public_html/wp-includes/PHPMailer/SMTP.php');
require_once('/public_html/wp-includes/PHPMailer/PHPMailer.php');
require_once('/public_html/wp-includes/PHPMailer/Exception.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Call to function.
send_email_reminders();

function send_email_reminders() {
	
	$today_timestamp = time();
	
	// WP_Agent_Query arguments
	$args_weekly = array(
		'fields' => 'ids',
		'post_status' => 'publish',
		'post_type'  => 'enter_your_post_type',
		'posts_per_page' => -1,
		'meta_key'     => 'validity_date',
		'meta_value'   => $today_timestamp,
		'type'    => 'numeric',
		'meta_compare' => '=',
	);
    
	// The Agent Query
	$email_reminders_query = new WP_Query( $args_weekly );
	
	if ( $email_reminders_query->have_posts() ) {
		
		$email_addresses = array();
		
		foreach ( $email_reminders_query->posts as $post ) {
			//Get the author email
			$author_id = get_post_field( 'post_author', $post );
			$authr_email = get_the_author_meta( 'user_email', $author_id );
			$email_addresses[] = $authr_email;
		}
		
		//Setting up email settings (email address is set below).
		$from = "no_reply@example.com";
		$from_name = "Sender name";
		$subject = "message subject";
	    
		$html_email_template_file = get_stylesheet_directory_uri().'/html/send_email_template.html';
		$message = file_get_contents($html_email_template_file);
	    
		$to = $email_addresses;
	
		if (!empty($email_addresses)) {
			custom_send_mass_mail_no_reply_account($from,$from_name,$subject,$message,$to);
		}
	}
}

//Email Reminders PHPMailer
function custom_send_mass_mail_no_reply_account($from,$from_name,$subject,$message,$to) {
    
	$mail = new PHPMailer(true);

	//Server settings
	$mail->isSMTP();
	$mail->Host       = 'mail.example.com';
	$mail->SMTPAuth   = true;
	$mail->Username   = 'no_reply@example.com';
	$mail->Password   = 'your_password';
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	$mail->Port       = 465;
	$mail->CharSet   = 'utf-8';
    
	//Recipients
	$mail->setFrom($from, $from_name);
    
	//Send a message to any email address in the array
	if(is_array($to)) {
		foreach($to as $t) {
			$mail->addBCC($t);
		}
	}

	//Content
	$mail->isHTML(true);
	$mail->Subject = $subject;
	$mail->Body    = $message;

	$mail->send();
    
}
