# Wordpres-email-reminder-on-specific-date
With this code snippet you can create an automatic email alert for all the posts on the site (of a specific post type) on a specific date set by a custom date field.
The alert is sent to the user who posted the post.

how to use?
1. Create an email message template in HTML (you can use a service like https://stripo.email). place the file inside the "html" folder in your template (if there is no such folder then create it). the file name will be "send_email_template.html"
2. Create a custom field in a date type post, where the field will be "validity_date" (you can use the ACF plugin or any other plugin).
3. Create a user to send emails on the server. update the code entries in the appropriate places
4. Before the functions there is a call to the PHPMailer files and the WordPress file. Please check that the path is correct on your site (you may need to update the path).
5. Change the name of the sender and the subject of the message in the code.
6. Put the PHP file of the code itself on your server in the location of your choice.
7. Create a cron job to run the file every day.
