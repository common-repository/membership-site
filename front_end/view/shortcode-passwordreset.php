<?php
if (!is_user_logged_in()) : ?>
    <div class="qazert">
        <div style="clear:both;">
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="wp-user-form">
                <?php if (!isset($_POST['reset_pass'])) { ?>
                    <div class="username">
                        <label for="user_login"><?php _e('Forgot your password?  Enter your email address here', 'membership-site'); ?>: </label>
                    </div>
                <?php } ?>
                <div class="login_fields">
                    <?php if (!isset($_POST['reset_pass'])) { ?>
                        <input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" /><input type="submit" name="user-submit" value="<?php _e('Reset my password', 'membership-site'); ?>" class="user-submit" tabindex="1002" />
                    <?php } ?>

                    <?php do_action('login_form', 'resetpass'); ?>
                    <?php
                        if (isset($_POST['reset_pass'])) {
                            $error = array();
                            global $wpdb, $wp_hasher;
                            $username = sanitize_user(trim($_POST['user_login']));
                            $user_exists = false;
                            if (username_exists($username)) {
                                $user_exists = true;
                                $user = get_userdatabylogin($username);
                            }
                            if (!$user_exists) {
                                if (email_exists($username)) {
                                    $user_exists = true;
                                    $user = get_user_by_email($username);
                                }
                            }
                            if (!$user_exists) {
                                $error[] = '<p>' . __('Username or Email was not found, try again!', 'membership-site') . '</p>';
                            }
                            if ($user_exists) {
                                $user_login = $user->user_login;
                                $user_email = $user->user_email;
                                $key = wp_generate_password(20, false);
                                if (empty($wp_hasher)) {
                                    require_once ABSPATH . 'wp-includes/class-phpass.php';
                                    $wp_hasher = new PasswordHash(8, true);
                                }
                                $hashed = time() . ':' . $wp_hasher->HashPassword($key);
                                $wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));
                                $message = __('Someone requested that the password be reset for the following account:', 'membership-site') . "\r\n\r\n";
                                $message .= network_home_url('/') . "\r\n\r\n";
                                $message .= sprintf(__('Username: %s', 'membership-site'), $user_login) . "\r\n\r\n";
                                $message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'membership-site') . "\r\n\r\n";
                                $message .= __('To reset your password, visit the following address:', 'membership-site') . "\r\n\r\n";
                                $message .= get_option('siteurl') . "/wp-login.php?action=rp&key=$key&login=" . urlencode($user_login) . "\r\n";
                                //send email meassage
                                if (FALSE == wp_mail($user_email, sprintf(__('[%s] Password Reset', 'membership-site'), get_option('blogname')), $message))
                                    $error[] = '<p>'
                                        . __('The e-mail could not be sent.', 'membership-site')
                                        . "<br />\n"
                                        . __('Possible reason: your host may have disabled the mail() function...', 'membership-site') . '</p>';
                            }
                            if (count($error) > 0) {
                                echo '<p class="alert alert-danger">';
                                foreach ($error as $e) {
                                    echo $e . '<br/>';
                                }
                                echo '</p>';
                            } else {
                                echo '<p class="alert alert-success" id="mspassresetmessage"><strong>' . __('A message has been sent to your email address.', 'membership-site') . '</strong></p>';
                                echo "<script>jQuery(document).ready(function() {jQuery('html, body').animate({scrollTop: jQuery('#mspassresetmessage').offset().top}, 'slow');});</script>";
                            }
                        } ?>
                    <input type="hidden" name="reset_pass" value="1" />
                    <input type="hidden" name="user-cookie" value="1" />
                </div>
            </form>
        </div>
    </div>

<?php endif; ?>