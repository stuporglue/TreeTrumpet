<h3>Enable E-Mail Contact</h3>
<p>Edit your config.php file to allow users to email you. You need to either:</p>
<ol><li>Set the email_address setting <strong>AND</strong>
    <ul>
        <li>Note: This will display your email address to the world!</li>
    </ul>
    </li>
</ol>
<p>or</p>
<ol>
    <li>Set the email_address setting <strong>AND</strong></li>
    <li>Set the show_email_form to TRUE
    <ul>
        <li>If PHP is not configued to send mail, you should configure the smtp_* settings for your mail provider. Settings for Gmail are included.</li>
        <li>If show_email_form is enabled you could disable show_email_address to hide your email address.</li>
    </ul>
    </li>
</ol>
