<?php

global $bizpanda;
$lang = $bizpanda->options['lang'];
?>

<div class="onp-help-section">
    <h1><?php _e('Email Locker', 'emaillocker'); ?></h1>

    <p>
        <?php _e('Email Locker locks a portion of content on a webpage, by hiding or blurring it, and asks the visitor to enter his email address to unlock it.', 'emaillocker') ?>
    </p>
    
    <p><?php _e('That gives users a strong reason to subscribe, turning visitors into subscribers and potential customers.') ?></strong></p>
    
    <p>
        <?php _e('Let\'s remember about traditional ways of list-building to see benefits of the Email Locker.', 'emaillocker') ?>
    </p>
    
    <table class="table">
        <thead>
            <tr>
                <th colspan="2"><?php _e('Traditional Ways', 'emaillocker') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="white-space: nowrap"><?php _e('Subscription forms', 'emaillocker') ?></td>
                <td><?php _e('Put in various places of websites (e.g. on sidebar, after posts). To make these forms work well, you need to write a strong call-to-action linked with the post content. But usually all of them have <span class="onp-stress">extremely low conversion</span>.') ?></td>
            </tr>
            <tr>
                <td style="white-space: nowrap"><?php _e('Popups', 'emaillocker') ?></td>
                <td><?php _e('Attract lots of attention but more and more <span class="onp-stress">users starts ignoring them</span>. Just because people face these identical popups on every website.', 'emaillocker') ?></td>                
            </tr>
            <tr>
                <td style="white-space: nowrap"><?php _e('Squeeze pages', 'emaillocker') ?></td>
                <td><?php _e('If perfectly designed, they can bring you plenty new subscribers. But they <span class="onp-stress">require redirecting traffic</span> to particular pages and <span class="onp-stress">plenty work to setup</span>. Usually used only as landing pages for specific promotion campaigns.', 'emaillocker') ?></td>
            </tr>
        </tbody>
    </table>
    
    <p>
        <?php _e('Email Locker aggregates well all the advantages of the traditional ways and avoids their weaknesses:', 'emaillocker') ?>
        <ul class="onp-list">
            <li><?php _e('The Email Locker creates a <span class="onp-stress">strong call-to-action</span>, by incentivizing the users to subscribe in return at unlocking some value content.', 'emaillocker') ?></li>
            <li><?php _e('The Email Locker is built into the post content and looks like an integral part of page. The user cannot close or skip it unlike popups. What\'s more, it <span class="onp-stress">attracts huge attention</span> like popups thanks to bright themes and effects.') ?></li>
            <li><?php _e('The Email Locker is flexible and highly configurable. You can put it anywhere, <span class="onp-stress">on any page at any place</span>, and make it looking relevant to any content.') ?></li>
        </ul>
    </p>

    <p style="margin-top: 25px;">
        <a href="<?php $manager->actionUrl('index', array( 'onp_sl_page' => 'usage-example-email-locker' )) ?>" class="btn btn-default"><?php _e('Learn how to configure and use Email Locker', 'emaillocker') ?><i class="fa fa-long-arrow-right"></i></a>
    </p>

</div>