<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DogApplication $application
 * @var string $applicantName
 * @var string $dogName
 * @var string $pickupMethod
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Approved!</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 40px; text-align: center; border-radius: 8px 8px 0 0;">
                            <div style="font-size: 60px; margin-bottom: 10px;">🎉</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: bold;">
                                Congratulations!
                            </h1>
                            <p style="margin: 10px 0 0; color: #ffffff; font-size: 18px; opacity: 0.95;">
                                Your adoption application has been approved!
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                Dear <?= h($applicantName) ?>,
                            </p>

                            <p style="margin: 0 0 20px; font-size: 18px; line-height: 1.6; color: #333333; font-weight: 500;">
                                We're thrilled to let you know that your application to adopt <strong style="color: #28a745;"><?= h($dogName) ?></strong> has been approved!
                                We believe you'll provide a wonderful forever home.
                            </p>

                            <!-- Success Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border-left: 4px solid #28a745; margin: 25px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 15px; font-size: 20px; color: #155724;">🐾 Welcome to the Family!</h3>
                                        <p style="margin: 0; font-size: 15px; color: #155724; line-height: 1.6;">
                                            <?= h($dogName) ?> is officially yours! We can't wait for you to meet your new best friend and begin this wonderful journey together.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Next Steps -->
                            <h3 style="margin: 30px 0 20px; font-size: 22px; color: #333333; border-bottom: 2px solid #28a745; padding-bottom: 10px;">
                                📝 Next Steps
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 4px; margin-bottom: 10px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #28a745;">
                                            <span style="display: inline-block; background-color: #28a745; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 14px; margin-right: 8px;">1</span>
                                            Contact Our Adoption Coordinator
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #555555; line-height: 1.5;">
                                            Please reach out to us at <a href="mailto:adoptions@hannahshaus.com" style="color: #28a745; text-decoration: none; font-weight: bold;">adoptions@hannahshaus.com</a>
                                            or call us at <strong>(555) 123-4567</strong> to schedule your pickup/delivery.
                                        </p>
                                    </td>
                                </tr>
                                <tr><td style="height: 15px;"></td></tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 4px; margin-bottom: 10px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #28a745;">
                                            <span style="display: inline-block; background-color: #28a745; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 14px; margin-right: 8px;">2</span>
                                            Pickup/Delivery Arrangement
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #555555; line-height: 1.5;">
                                            Your selected method: <strong><?= h($pickupMethod) ?></strong><br>
                                            We'll coordinate a convenient time within the next 5-7 business days.
                                        </p>
                                    </td>
                                </tr>
                                <tr><td style="height: 15px;"></td></tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 4px; margin-bottom: 10px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #28a745;">
                                            <span style="display: inline-block; background-color: #28a745; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 14px; margin-right: 8px;">3</span>
                                            Prepare for Arrival
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #555555; line-height: 1.5;">
                                            Make sure you have the essentials ready: food and water bowls, a comfortable bed,
                                            toys, leash and collar, and dog food. We'll provide you with <?= h($dogName) ?>'s
                                            current diet and any special care instructions.
                                        </p>
                                    </td>
                                </tr>
                                <tr><td style="height: 15px;"></td></tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #f8f9fa; border-radius: 4px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #28a745;">
                                            <span style="display: inline-block; background-color: #28a745; color: #ffffff; width: 24px; height: 24px; border-radius: 50%; text-align: center; line-height: 24px; font-size: 14px; margin-right: 8px;">4</span>
                                            Complete Adoption Agreement
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #555555; line-height: 1.5;">
                                            We'll send you the adoption agreement to review and sign. This ensures <?= h($dogName) ?>
                                            receives the love and care they deserve in their new home.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Information -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; margin: 25px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #856404;">
                                            ⚠️ Important Information
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #856404; line-height: 1.5;">
                                            Please respond to this email or contact us within <strong>48 hours</strong> to confirm
                                            you're still interested in adopting <?= h($dogName) ?>. If we don't hear from you,
                                            we may need to consider other applicants.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                We're so excited for you and <?= h($dogName) ?>! Thank you for choosing to adopt and giving
                                a deserving dog a loving home. If you have any questions, please don't hesitate to reach out.
                            </p>

                            <p style="margin: 0; font-size: 16px; line-height: 1.6; color: #333333;">
                                With gratitude,<br>
                                <strong>The Hannah's Haus Team</strong>
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
                            <p style="margin: 0 0 10px; font-size: 13px; color: #999999; text-align: center;">
                                📧 adoptions@hannahshaus.com | ☎️ (555) 123-4567
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
