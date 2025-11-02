<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DogApplication $application
 * @var string $applicantName
 * @var string $dogName
 * @var string $applicantEmail
 * @var string $applicantPhone
 * @var string $housingType
 * @var bool $hasChildren
 * @var bool $everOwnedDogs
 * @var bool $primaryCareTaker
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Application for Review</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <div style="font-size: 48px; margin-bottom: 10px;">🔔</div>
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: bold;">
                                New Adoption Application
                            </h1>
                            <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.95;">
                                Requires your review and decision
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 25px; font-size: 16px; line-height: 1.6; color: #333333;">
                                A new adoption application has been submitted for <strong style="color: #ff6b35;"><?= h($dogName) ?></strong>
                                and is ready for your review.
                            </p>

                            <!-- Applicant Details -->
                            <h3 style="margin: 0 0 15px; font-size: 20px; color: #333333; border-bottom: 2px solid #ff6b35; padding-bottom: 10px;">
                                👤 Applicant Information
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff8f5; border-left: 4px solid #ff6b35; margin: 20px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px; font-size: 15px; color: #333333;">
                                            <strong>Name:</strong> <?= h($applicantName) ?>
                                        </p>
                                        <p style="margin: 0 0 10px; font-size: 15px; color: #333333;">
                                            <strong>Email:</strong> <a href="mailto:<?= h($applicantEmail) ?>" style="color: #ff6b35; text-decoration: none;"><?= h($applicantEmail) ?></a>
                                        </p>
                                        <p style="margin: 0 0 10px; font-size: 15px; color: #333333;">
                                            <strong>Phone:</strong> <?= h($applicantPhone) ?>
                                        </p>
                                        <p style="margin: 0; font-size: 15px; color: #333333;">
                                            <strong>Submitted:</strong> <?= h($application->dateCreated->format('F j, Y \a\t g:i A')) ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Housing & Qualifications -->
                            <h3 style="margin: 30px 0 15px; font-size: 20px; color: #333333; border-bottom: 2px solid #ff6b35; padding-bottom: 10px;">
                                🏠 Housing & Qualifications
                            </h3>

                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="padding: 12px; background-color: #f8f9fa; border-radius: 4px;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #555555;">
                                                    <strong>Housing Type:</strong>
                                                </td>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #333333;">
                                                    <?= h($housingType) ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #555555;">
                                                    <strong>Has Children:</strong>
                                                </td>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #333333;">
                                                    <?= $hasChildren ? 'Yes' : 'No' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #555555;">
                                                    <strong>Owned Dogs Before:</strong>
                                                </td>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #333333;">
                                                    <?= $everOwnedDogs ? 'Yes' : 'No' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #555555;">
                                                    <strong>Primary Caretaker:</strong>
                                                </td>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #333333;">
                                                    <?= $primaryCareTaker ? 'Yes' : 'No' ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #555555;">
                                                    <strong>Pickup Method:</strong>
                                                </td>
                                                <td width="50%" style="padding: 5px 10px; font-size: 14px; color: #333333;">
                                                    <?= h($application->pickup_method->name) ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action Required Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; margin: 25px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #856404;">
                                            ⚡ Action Required
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #856404; line-height: 1.5;">
                                            Please review this application and make a decision. The applicant is waiting
                                            to hear back within 3-5 business days.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table cellpadding="0" cellspacing="0" style="margin: 25px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="http://localhost/dog-application/admin-dashboard" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            Review Application
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0; font-size: 14px; line-height: 1.6; color: #666666; text-align: center;">
                                This is an automated notification from the Hannah's Haus adoption system.<br>
                                Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 30px; border-radius: 0 0 8px 8px; border-top: 1px solid #e9ecef;">
                            <p style="margin: 0 0 10px; font-size: 14px; color: #666666; text-align: center;">
                                <strong>Hannah's Haus Dog Adoption</strong><br>
                                Admin Dashboard
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
