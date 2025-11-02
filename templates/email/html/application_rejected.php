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
    <title>Application Update</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- Main container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: bold;">
                                Adoption Application Update
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
                                Thank you so much for your interest in adopting <strong><?= h($dogName) ?></strong> and for taking
                                the time to submit an adoption application. We truly appreciate your desire to provide a loving home.
                            </p>

                            <p style="margin: 0 0 25px; font-size: 16px; line-height: 1.6; color: #333333;">
                                After careful consideration, we've decided to move forward with another applicant for <?= h($dogName) ?>.
                                This was not an easy decision, as we had several wonderful applications to review.
                            </p>

                            <!-- Encouragement Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #e7f3ff; border-left: 4px solid #0066cc; margin: 25px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <h3 style="margin: 0 0 15px; font-size: 18px; color: #004085;">
                                            💙 You're an Excellent Adopter!
                                        </h3>
                                        <p style="margin: 0; font-size: 15px; color: #004085; line-height: 1.6;">
                                            Please don't be discouraged! This decision doesn't reflect on your ability to provide
                                            a wonderful home. Finding the right match for each dog involves many factors, and we're
                                            confident you'll be a perfect match for another dog in need.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Available Dogs -->
                            <h3 style="margin: 30px 0 20px; font-size: 20px; color: #333333; border-bottom: 2px solid #0066cc; padding-bottom: 10px;">
                                🐕 Other Dogs Waiting for You
                            </h3>

                            <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                We have many other wonderful dogs who are looking for their forever homes. Each one has a unique
                                personality and would love to meet you!
                            </p>

                            <!-- CTA Button -->
                            <table cellpadding="0" cellspacing="0" style="margin: 25px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="http://localhost/dogs/index" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                            Browse Available Dogs
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                                We encourage you to explore our other available dogs and submit another application when you
                                find one that captures your heart. Your profile is still active in our system, making it easy
                                to apply again.
                            </p>

                            <!-- Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8f9fa; border-left: 4px solid #6c757d; margin: 25px 0; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h4 style="margin: 0 0 10px; font-size: 16px; color: #495057;">
                                            📞 Need Help Finding Your Match?
                                        </h4>
                                        <p style="margin: 0; font-size: 14px; color: #495057; line-height: 1.5;">
                                            Our adoption counselors are happy to help you find the perfect companion!
                                            Contact us at <a href="mailto:info@hannahshaus.com" style="color: #0066cc; text-decoration: none; font-weight: bold;">info@hannahshaus.com</a>
                                            or call <strong>(555) 123-4567</strong> to discuss which dogs might be the best fit for your home.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0; font-size: 16px; line-height: 1.6; color: #333333;">
                                Thank you again for considering adoption. We're grateful for people like you who open their
                                hearts and homes to dogs in need. We hope to help you find your perfect match soon!
                            </p>

                            <p style="margin: 20px 0 0; font-size: 16px; line-height: 1.6; color: #333333;">
                                With warm regards,<br>
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
                                📧 info@hannahshaus.com | ☎️ (555) 123-4567
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
