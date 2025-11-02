<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DogApplication $application
 * @var string $applicantName
 * @var string $dogName
 */
?>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
HANNAH'S HAUS DOG ADOPTION
Application Received!
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Dear <?= $applicantName ?>,

Thank you for your interest in adopting <?= $dogName ?>! We've received
your adoption application and are excited to review it.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📋 APPLICATION SUMMARY
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Dog:            <?= $dogName ?>

Submitted:      <?= $application->dateCreated->format('F j, Y') ?>

Pickup Method:  <?= $application->pickup_method->name ?>


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
WHAT HAPPENS NEXT?
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

1. Our team will review your application carefully

2. We may contact you for additional information

3. You'll receive an email with our decision within 3-5 business days


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

You can check the status of your application anytime by visiting:
http://localhost/dog-application/my-applications

If you have any questions, please don't hesitate to contact us at:
info@hannahshaus.com


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Hannah's Haus Dog Adoption
Giving dogs a second chance at happiness

© <?= date('Y') ?> Hannah's Haus. All rights reserved.
