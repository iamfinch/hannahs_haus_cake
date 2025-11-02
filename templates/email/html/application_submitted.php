<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DogApplication $application
 * @var string $applicantName
 * @var string $dogName
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Received</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                Application Received!
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                Dear <?= h($applicantName) ?>,
                            </p>

                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                Thank you for your interest in adopting <strong style="color: #667eea;"><?= h($dogName) ?></strong>!
                                We've received your adoption application and are excited to review it.
                            </p>

                            <!-- Application Details Card -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-left: 4px solid #667eea; margin: 20px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 15px; font-size: 18px; color: #667eea;">📋 Application Summary</h3>
                                        <p style="margin: 0 0 10px; font-size: 14px; color: #666666;">
                                            <strong>Dog:</strong> <?= h($dogName) ?>
                                        </p>
                                        <p style="margin: 0 0 10px; font-size: 14px; color: #666666;">
                                            <strong>Submitted:</strong> <?= h($application->dateCreated->format('F j, Y')) ?>
                                        </p>
                                        <p style="margin: 0 0 10px; font-size: 14px; color: #666666;">
                                            <strong>Pickup Method:</strong> <?= h($application->pickup_method->name) ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Next Steps -->
                            <h3 style="margin: 30px 0 15px; font-size: 20px; color: #333333;">What Happens Next?</h3>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <div style="display: inline-block; background-color: #667eea; color: #ffffff; width: 28px; height: 28px; border-radius: 50%; text-align: center; line-height: 28px; font-weight: bold; margin-right: 10px;">1</div>
                                        <span style="font-size: 15px; color: #555555;">Our team will review your application carefully</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <div style="display: inline-block; background-color: #667eea; color: #ffffff; width: 28px; height: 28px; border-radius: 50%; text-align: center; line-height: 28px; font-weight: bold; margin-right: 10px;">2</div>
                                        <span style="font-size: 15px; color: #555555;">We may contact you for additional information</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <div style="display: inline-block; background-color: #667eea; color: #ffffff; width: 28px; height: 28px; border-radius: 50%; text-align: center; line-height: 28px; font-weight: bold; margin-right: 10px;">3</div>
                                        <span style="font-size: 15px; color: #555555;">You'll receive an email with our decision within 3-5 business days</span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                You can check the status of your application anytime by visiting your
                                <a href="http://localhost/dog-application/my-applications" style="color: #667eea; text-decoration: none; font-weight: bold;">My Applications</a> page.
                            </p>

                            <p style="margin: 0; font-size: 16px; line-height: 1.6; color: #333333;">
                                If you have any questions, please don't hesitate to contact us at
                                <a href="mailto:info@hannahshaus.com" style="color: #667eea; text-decoration: none;">info@hannahshaus.com</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 30px; border-radius: 0 0 8px 8px; border-top: 1px solid #e9ecef;">
                            <p style="margin: 0 0 10px; font-size: 14px; color: #666666; text-align: center;">
                                <strong>Hannah's Haus Dog Adoption</strong><br>
                                Giving dogs a second chance at happiness
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #999999; text-align: center;">
                                © <?= date('Y') ?> Hannah's Haus. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
