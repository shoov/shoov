<?php
/**
 * @file
 * Theme implementation to format an HTML mail.
 *
 * Available variables:
 * - $recipient: The recipient of the message
 * - $subject: The message subject
 * - $body: The message body
 * - $css: Internal style sheets
 * - $module: The sending module
 * - $key: The message identifier
 *
 * @see template_preprocess_mimemail_message()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="he" dir="rtl">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width"/>

  <?php if($css): ?>
    <style type="text/css">
      <?php print $css; ?>
    </style>
  <?php endif; ?>
</head>
<body style="background-color: #e6e6e6;">

<table class="body" style="background-color: #e6e6e6;direction:rtl!important;">
  <tr>
    <td>
      <table class="container">
        <tbody>
        <tr>
          <td>
            <!-- Ethosia Logo -->
            <table class="row header">
              <tbody><tr>
                <td class="wrapper">
                  <table class="twelve columns">
                    <tbody><tr>
                      <td class="expander" style="width:450px"></td>
                      <td style="padding-left: 10px;">
                        <img class="logo" alt="ethosia" src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/logo.png">
                      </td>
                      <td class="expander"></td>
                    </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
              </tbody></table>

            <div style="direction:rtl;background-color: white; margin: 0 auto;">
              <!-- Header Imager -->
              <img style="direction:rtl;display:inline;float:none; margin-bottom: -6px;" src="http://ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/header-welcome-full.png">
              <table class="row main" style="direction:rtl;margin: 0 auto; text-align: right;">
                <tbody>
                <tr>
                  <td class="wrapper">
                    <table class="twelve columns">
                      <tbody><tr style="direction:rtl;text-align: right; margin: 0 auto;">
                        <td class="blue-big-text" style="direction:rtl;color: #6bb9e7;font-size: 20px;font-weight: bold;margin: 10px 3px 10px 0px;text-align: right;">
                          <?php print $subject; ?>
                        </td>
                        <td class="expander"></td>
                      </tr>
                      </tbody></table>
                  </td>
                  <td class="expander"></td>
                </tr>
                <tr>
                  <td class="wrapper">
                    <table class="twelve columns blue-links">
                      <tbody>
                      <?php print $body; ?>
                      </tbody>
                    </table>
                  </td>
                  <td class="expander"></td>
                </tr>
                <tr>
                  <td class="wrapper padding-left">
                    <hr class="separator">
                  </td>
                  <td class="expander"></td>
                </tr>
                <tr>
                  <td class="center wrapper" align="center" valign="top">
                    <center>
                      <table>
                        <tbody><tr>
                          <td class="sub-title">אנחנו באתוסיה משקיעים בפיתוח כלים רבים, שיסייעו לך בניהול ובפיתוח הקריירה שלך</td>
                        </tr>
                        </tbody></table>
                    </center>
                  </td>
                  <td class="expander"></td>
                </tr>
                <tr>
                  <td class="wrapper">&nbsp;</td><td class="expander"></td></tr>
                <tr>
                  <td class="wrapper padding-left">
                    <table class="twelve columns">
                      <tbody><tr>
                        <td class="center" align="center" valign="top">
                          <center>
                            <table class="boxes-images">
                              <tr>
                                <td><a
                                    href="http://www.ethosia.co.il/salary_report"
                                    title="סקרי שכר רבעוניים בהייטק וביוטק"><img
                                      src="<?php print $images_path; ?>/salaries.png"
                                      alt="סקרי שכר רבעוניים בהייטק וביוטק"/></a>
                                </td>
                                <td><a href="http://www.ethosia.co.il/search"
                                       title="מנוע חיפוש מותאם אישי לאיתור משרות"><img
                                      src="<?php print $images_path; ?>/search.png"
                                      alt="מנוע חיפוש מותאם אישי לאיתור משרות"/></a>
                                </td>
                                <td><a href="http://www.ethosia.co.il/"
                                       title="התראה לעדכון על משרות חדשות"><img
                                      src="<?php print $images_path; ?>/agent.png"
                                      alt="התראה לעדכון על משרות חדשות"/></a>
                                </td>
                              </tr>
                              <tr>
                                <td><a
                                    href="http://www.ethosia.co.il/article-categories"
                                    title="מאמרים מקצועיים בתחום פיתוח וניהול קריירה"><img
                                      src="<?php print $images_path; ?>/blog.png"
                                      alt="מאמרים מקצועיים בתחום פיתוח וניהול קריירה"/></a>
                                </td>
                                <td><a href="http://www.ethosia.co.il/companies"
                                       title="אינדקס חברות הייטק וביוטק"><img
                                      src="<?php print $images_path; ?>/companies.png"
                                      alt="אינדקס חברות הייטק וביוטק"/></a></td>
                                <td><a href="http://www.ethosia.co.il/"
                                       title="מגוון תחומי גיוס"><img
                                      src="<?php print $images_path; ?>/coffee.png"
                                      alt="מגוון תחומי גיוס"/></a></td>
                              </tr>
                            </table>
                          </center>
                        </td>
                      </tr>

                      </tbody></table>
                  </td>
                  <td class="expander"></td>
                </tr>
                <tr>
                  <td class="wrapper">&nbsp;</td><td class="expander"></td>
                </tr>
                </tbody>
              </table>
            </div>
            <table class="row footer" style="background:#000000;max-width: 612px;width: 100%">
              <tr>
                <td class="wrapper">

                  <table class="twelve columns" style="margin: 0 auto;">
                    <tr>
                      <td class="center social-icons_td" align="center" valign="top">
                        <center>
                          <table class="social-icons">
                            <tr>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/youtube.png"></a>
                              </td>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/linkedin.png"></a>
                              </td>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/googleplus.png"></a>
                              </td>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/twitter.png"></a>
                              </td>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/facebook.png"></a>
                              </td>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/website.png"></a>
                              </td>
                              <td>
                                <a href="#"><img src="http://www.ethosia.co.il/profiles/ethosia/libraries/ethosia/images/email/phone.png"></a>
                              </td>
                            </tr>
                          </table>
                        </center>
                      </td>
                      <td class="expander"></td>
                    </tr>
                  </table>

                </td>
                <td class="expander"></td>
              </tr>
              <tr>
                <td class="center footer-text_td" align="center" valign="top">
                  <center>
                    <table class="footer-text">
                      <tr>
                        <td>
                          <a href="mailto:info@ethosia.com"><img src="http://dev-ethosia.gotpantheon.com/profiles/ethosia/libraries/ethosia/images/email/bottom_link_mail.png"></a>
                        </td>
                        <td>|</td>
                        <td>
                          <a href="#"><img src="http://dev-ethosia.gotpantheon.com/profiles/ethosia/libraries/ethosia/images/email/bottom_link_ethosia.png"></a>
                        </td>
                      </tr>
                      <tr>
                        <td></td>
                      </tr>
                    </table>
                  </center>
                </td>
                <td class="expander"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>
</html>
