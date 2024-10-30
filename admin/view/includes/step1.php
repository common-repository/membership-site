      <form method="post">
          <table class="table table-borderless" style="width:auto;">
              <tr>
                  <td class="align-middle">
                      <?php
                        _e('Enter Product/Membership Title:', 'membership-site');
                        ?></td>
                  <?php
                    $membership_level_name = isset($membership_level['membership_level_name']) ? $membership_level['membership_level_name'] : '';
                    ?>
                  <td><input type="text" class="form-control" required name="membership_level_name" value="<?php echo $membership_level_name;  ?>" /></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td><?php _e('Select Thank You/OTO Page:', 'membership-site'); ?></td>
                  <?php $redirect_page = isset($membership_level['redirect_page']) ? $membership_level['redirect_page'] : ''; ?>
                  <td><select class="form-control" name="redirect_pagesel" id="redirectpage">
                          <option value="">-</option>
                          <?php
                            foreach ($pages as $page) { ?>
                              <option value="<?php echo get_permalink($page->ID); ?>" <?php echo get_permalink($page->ID) == $redirect_page ? 'selected=selected' : ''; ?>>
                                  <?php echo $page->post_title; ?>
                              </option>
                          <?php  } ?>
                      </select><br /> <?php _e('or Custom URL', 'membership-site'); ?><input name="redirect_page" id="redirect_page" value="<?php echo esc_url($membership_level['redirect_page']);  ?>" type="text" class="form-control" /></td>

                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td class="align-middle"><?php _e('After Login Page:', 'membership-site'); ?></td>
                  <?php
                    $otherinfo            = json_decode($membership_level['other_info'], true);
                    $after_login_memlevel = $otherinfo['after_login_memlevel'];
                    $after_login_memlevel = isset($after_login_memlevel) ? $after_login_memlevel : '';
                    ?>
                  <td><select class="form-control" required name="after_login_memlevel">
                          <option value="">Select</option>
                          <?php
                            foreach ($pages as $page) {
                                ?>
                              <option value="<?php
                                                    echo get_permalink($page->ID);
                                                    ?>" <?php
                                                            echo get_permalink($page->ID) == $after_login_memlevel ? 'selected=selected' : '';
                                                            ?>>
                                  <?php
                                        echo $page->post_title;
                                        ?>
                              </option>
                          <?php
                            }
                            ?>
                      </select></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td class="align-middle"> <?php _e('Disable free registration forms:', 'membership-site'); ?> </td>
                  <?php
                    $paid_memlevel = $otherinfo['paid_memlevel'];
                    ?>
                  <td><input name="paid_memlevel" type="checkbox" value="on" <?php echo ($paid_memlevel == "on") ? ' checked ' : '' ?> value="1" /></td>
                  <td></td>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
                  <td></td>
                  <td>
                      <?php
                      $uniqid = uniqid('msstep1');
                      wp_nonce_field($uniqid); ?>
                      <input type="hidden" id="uniqid" name="uniqid" value="<?php echo $uniqid; ?>" />
                      <input type="hidden" name="membership_level_id" value="<?php echo $membership_level['id']; ?>" />
                      <input type="hidden" name="mslitetab" value="step1" />
                      <input type="hidden" name="msliteaction" value="savestep1" />
                      <input type="submit" class="btn btn-md btn-ms w-100" id="memsubmitbut" name="submit" value="Save" /></td>
                  <td></td>
                  <td></td>
              </tr>
          </table>

      </form>