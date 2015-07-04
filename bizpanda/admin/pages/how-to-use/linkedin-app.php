<div class="onp-help-section">
    <h1><?php _e('Getting LinkedIn API Key', 'bizpanda'); ?></h1>

    <p>
        <?php _e('A LinkedIn API Key is required for the following buttons:', 'bizpanda'); ?>
        <ul>
            <li><?php _e('LinkedIn Sign-In of the Sign-In Locker.', 'bizpanda') ?></li>
            <?php if ( BizPanda::hasPlugin('optinpanda') ) { ?>
            <li><?php _e('LinkedIn Subscribe of the Email Locker.', 'bizpanda') ?></li>     
            <?php } ?>
        </ul>
    </p>
    
    <p><?php _e('If you want to use these buttons, you need to get create a LinkedIn App for your website and generate an API key.', 'bizpanda') ?>
    <?php _e('<strong>You don\'t need to do it</strong> if you\'re not going to use these LinkedIn buttons.') ?></p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('1. Go to the <a href="%s" target="_blank">LinkedIn Developer Network</a> and click <strong>Add New Application</strong>.', 'bizpanda'), 'https://www.linkedin.com/secure/developer' ) ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('2. Fill up the groups <strong>Company Info</strong>, <strong>Application Info</strong> and <strong>Contact Info</strong> the following way:', 'bizpanda' ) ?></p>
    <table class="table">
        <thead>
            <tr>
                <th><?php _e('Field', 'bizpanda') ?></th>
                <th><?php _e('How To Fill', 'bizpanda') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="onp-title"><?php _e('Company', 'bizpanda') ?></td>
                <td><?php _e('Select an existing company or create your own one (you can use your website name as a company name).', 'bizpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Application Name', 'bizpanda') ?></td>
                <td><?php _e('The best name is your website name.', 'bizpanda') ?></td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Description', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Explain what your app does, e.g:', 'bizpanda') ?></p>
                    <p><i><?php _e('This application asks your credentials in order to unlock the content. Please read the Terms of Use to know how these credentials will be used.', 'bizpanda') ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Website URL', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Paste your website URL:', 'bizpanda') ?></p>
                    <p><i><?php echo site_url() ?></i></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Application Use', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Select "Other" from the list.', 'bizpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Application Developers', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Make sure that the checkbox "Include yourself as a developer for this application" is marked.', 'bizpanda') ?></p>
                </td>
            </tr>
            <tr>
                <td class="onp-title"><?php _e('Live Status', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Select "Live".', 'bizpanda') ?></p>
                </td>
            </tr> 
            <tr>
                <td class="onp-title"><?php _e('Developer Contact Email', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Enter your email to receive updates regarding your app.', 'bizpanda') ?></p>
                </td>
            <tr>
                <td class="onp-title"><?php _e('Phone', 'bizpanda') ?></td>
                <td>
                    <p><?php _e('Enter your phone. It will not be visible.', 'bizpanda') ?></p>
                </td>
            </tr>
        </tbody>
    </table>
    <p><?php _e('Check out the image below to make sure that you filled the fields correctly:', 'bizpanda') ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/linkedin-app/1.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('3. In the group <strong>OAuth User Agreement</strong> mark "<i>r_basicprofile</i>" and "<i>r_emailaddress</i>". In the list <strong>Agreement Language</strong>, select <i>Browser Locale Setting</i>.', 'bizpanda' ) ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/linkedin-app/2.png' />
    </p>
</div>

<?php
    $origin = null;
    $pieces = parse_url( site_url() );
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        $origin = 'http://' . $regs['domain'];
    }
?>

<div class="onp-help-section">
    <p style="margin: 0 0 5px 0;"><?php _e('4. And finally in the field <strong>JavaScript API Domains</strong> specify your website URL:', 'bizpanda' ) ?></p>
    <p style="margin: 0px;"><i><?php echo $origin ?></i></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/linkedin-app/3.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php _e('5. Agree to the LinkedIn terms and submit the form.', 'bizpanda')  ?></p>
</div>

<div class="onp-help-section">
    <p><?php _e('6. If you filled the form correctly, you will see your app details on the next screen.', 'bizpanda')  ?></p>
    <p class='onp-img'>
        <img src='http://cconp.s3.amazonaws.com/bizpanda/linkedin-app/4.png' />
    </p>
</div>

<div class="onp-help-section">
    <p><?php printf( __('7. Copy & paste your API Key on the page Global Settings > <a href="%s">Social Options</a>.', 'bizpanda' ), admin_url('admin.php?page=settings-bizpanda&opanda_screen=social') ) ?></p>
</div>