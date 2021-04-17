<?php 
$active_languages = (!empty($settings['active_languages']) ? $settings['active_languages'] : array());
$languages = wpbs_get_languages();
?>

<h2><?php echo __('Square', 'wp-booking-system-square') ?><?php echo wpbs_get_output_tooltip(__("Give the customer the option to pay with a credit card using Square.", 'wp-booking-system-square'));?></h2>

<!-- Enable Square -->
<div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
	<label class="wpbs-settings-field-label" for="payment_square_enable">
        <?php echo __( 'Active', 'wp-booking-system-square'); ?>
    </label>

	<div class="wpbs-settings-field-inner">
        <label for="payment_square_enable" class="wpbs-checkbox-switch">
            <input data-target="#wpbs-payment-square" name="wpbs_settings[payment_square_enable]" type="checkbox" id="payment_square_enable"  class="regular-text wpbs-settings-toggle wpbs-settings-wrap-toggle" <?php echo ( !empty( $settings['payment_square_enable'] ) ) ? 'checked' : '';?> >
            <div class="wpbs-checkbox-slider"></div>
        </label>
	</div>
</div>

<div id="wpbs-payment-square" class="wpbs-payment-on-arrival-wrapper wpbs-settings-wrapper <?php echo ( !empty($settings['payment_square_enable']) ) ? 'wpbs-settings-wrapper-show' : '';?>">

    <!-- Payment Method Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_square_name">
                <?php echo __( 'Display name', 'wp-booking-system-square'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method name that appears on the booking form.", 'wp-booking-system-square'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_square_name]" type="text" id="payment_square_name"  class="regular-text" value="<?php echo ( !empty( $settings['payment_square_name'] ) ) ? $settings['payment_square_name'] : $defaults['display_name'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-square'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_square_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_square_name_translation_<?php echo $language; ?>]" type="text" id="payment_square_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_square_name_translation_'. $language])) ? esc_attr($settings['payment_square_name_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Payment Method Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_square_description">
                <?php echo __( 'Description', 'wp-booking-system-square'); ?>
                <?php echo wpbs_get_output_tooltip(__("The payment method description that appears on the booking form.", 'wp-booking-system-square'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_square_description]" type="text" id="payment_square_description"  class="regular-text" value="<?php echo ( !empty( $settings['payment_square_description'] ) ) ? $settings['payment_square_description'] : $defaults['description'];?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-square'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_square_description_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_square_description_translation_<?php echo $language; ?>]" type="text" id="payment_square_description_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_square_description_translation_'. $language])) ? esc_attr($settings['payment_square_description_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Invoice Item Name -->
    <div class="wpbs-settings-field-translation-wrapper">
        <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_square_invoice_name">
                <?php echo __( 'Invoice Item Name', 'wp-booking-system-square'); ?>
                <?php echo wpbs_get_output_tooltip(__('The name of the product that appears on the Square invoice. Eg. "Booking at Anne\'s house."', 'wp-booking-system-square'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <input name="wpbs_settings[payment_square_invoice_name]" type="text" id="payment_square_invoice_name"  class="regular-text" value="<?php echo ( !empty( $settings['payment_square_invoice_name'] ) ) ? $settings['payment_square_invoice_name'] : '';?>" >
                <?php if (wpbs_translations_active()): ?><a href="#" class="wpbs-settings-field-show-translations"><?php echo __('Translations', 'wp-booking-system-square'); ?> <i class="wpbs-icon-down-arrow"></i></a><?php endif?>
            </div>
        </div>
        <?php if (wpbs_translations_active()): ?>
        <!-- Required Field Translations -->
        <div class="wpbs-settings-field-translations">
            <?php foreach ($active_languages as $language): ?>
                <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
                    <label class="wpbs-settings-field-label" for="payment_square_invoice_name_translation_<?php echo $language; ?>"><img src="<?php echo WPBS_PLUGIN_DIR_URL; ?>/assets/img/flags/<?php echo $language; ?>.png" /> <?php echo $languages[$language]; ?></label>
                    <div class="wpbs-settings-field-inner">
                        <input name="wpbs_settings[payment_square_invoice_name_translation_<?php echo $language; ?>]" type="text" id="payment_square_invoice_name_translation_<?php echo $language; ?>" value="<?php echo (!empty($settings['payment_square_invoice_name_translation_'. $language])) ? esc_attr($settings['payment_square_invoice_name_translation_'. $language]) : ''; ?>" class="regular-text" >
                    </div>
                </div>
            <?php endforeach;?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Authorize now, capture later -->
        <!-- <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
            <label class="wpbs-settings-field-label" for="payment_square_delayed_capture">
                <?php echo __( 'Capture payment when accepting booking', 'wp-booking-system-square'); ?>
                <?php echo wpbs_get_output_tooltip(__('If enabled, when the client makes a payment, his credit card will only be Authorized (the money will be put on hold for 7 days) and the payment will be Captured (money transfered in your account) only when you Accept the booking. Accepting the booking after 7 days will result in a failed payment.', 'wp-booking-system-square'));?>
            </label>

            <div class="wpbs-settings-field-inner">
                <label for="payment_square_delayed_capture" class="wpbs-checkbox-switch">
                    <input name="wpbs_settings[payment_square_delayed_capture]" type="checkbox" id="payment_square_delayed_capture"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty( $settings['payment_square_delayed_capture'] ) ) ? 'checked' : '';?> >
                    <div class="wpbs-checkbox-slider"></div>
                </label>
            </div>

            
        </div> -->


    <!-- API Settings -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-heading wpbs-settings-field-large">
        <label class="wpbs-settings-field-label"><?php echo __('API Credentials','wp-booking-system-square') ?></label>
        <div class="wpbs-settings-field-inner">&nbsp;</div>
    </div>

    <!-- Documentation -->
    <div class="wpbs-page-notice notice-info wpbs-form-changed-notice"> 
        <p><?php echo __( 'If you need help getting your API Keys, <a target="_blank" href="https://www.wpbookingsystem.com/documentation/square-integration/">check out our guide</a> which offers step by step instructions.', 'wp-booking-system-square'); ?></p>
    </div>

    <!-- Environment -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <div class="wpbs-settings-field-inner">
            <label class="wpbs-settings-field-label" for="user_notification_enable">
                <?php echo __( 'Enable Test Mode', 'wp-booking-system-square'); ?>
                <?php echo wpbs_get_output_tooltip(__("We recommend enabling test mode and testing the payment integration before going live.", 'wp-booking-system-square'));?>
            </label>
            <label for="payment_square_test" class="wpbs-checkbox-switch">
                <input name="wpbs_settings[payment_square_test]" type="checkbox" id="payment_square_test"  class="regular-text wpbs-settings-toggle" <?php echo ( !empty( $settings['payment_square_test'] ) ) ? 'checked' : '';?> >
                <div class="wpbs-checkbox-slider"></div>
            </label>
        </div>
    </div>

    <!-- Location ID -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="location_id">
            <?php echo __( 'Location ID', 'wp-booking-system-square'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_square_api[location_id]" type="text" id="location_id"  class="regular-text " value="<?php echo ( !empty( $square_api['location_id'] ) ) ? $square_api['location_id'] : '';?>" >
        </div>
    </div>

    <!-- Test Client ID -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_square_test_api_application_id">
            <?php echo __( 'Test Application ID', 'wp-booking-system-square'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_square_api[payment_square_test_api_application_id]" type="text" id="payment_square_test_api_application_id"  class="regular-text " value="<?php echo ( !empty( $square_api['payment_square_test_api_application_id'] ) ) ? $square_api['payment_square_test_api_application_id'] : '';?>" >
        </div>
    </div>
    
    <!-- Test Client Secret -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_square_test_api_access_token">
            <?php echo __( 'Test Access Token', 'wp-booking-system-square'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_square_api[payment_square_test_api_access_token]" type="text" id="payment_square_test_api_access_token"  class="regular-text " value="<?php echo ( !empty( $square_api['payment_square_test_api_access_token'] ) ) ? $square_api['payment_square_test_api_access_token'] : '';?>" >
        </div>
    </div>

    <!-- Live Client ID -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_square_live_api_application_id">
            <?php echo __( 'Live Application ID', 'wp-booking-system-square'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_square_api[payment_square_live_api_application_id]" type="text" id="payment_square_live_api_application_id"  class="regular-text " value="<?php echo ( !empty( $square_api['payment_square_live_api_application_id'] ) ) ? $square_api['payment_square_live_api_application_id'] : '';?>" >
        </div>
    </div>
    
    <!-- Live Client Secret -->
    <div class="wpbs-settings-field-wrapper wpbs-settings-field-inline wpbs-settings-field-large">
        <label class="wpbs-settings-field-label" for="payment_square_live_api_access_token">
            <?php echo __( 'Live Access Token', 'wp-booking-system-square'); ?>
        </label>

        <div class="wpbs-settings-field-inner">
            <input name="wpbs_square_api[payment_square_live_api_access_token]" type="text" id="payment_square_live_api_access_token"  class="regular-text " value="<?php echo ( !empty( $square_api['payment_square_live_api_access_token'] ) ) ? $square_api['payment_square_live_api_access_token'] : '';?>" >
        </div>
    </div>


</div>