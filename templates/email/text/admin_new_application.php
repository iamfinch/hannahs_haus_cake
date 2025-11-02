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
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
HANNAH'S HAUS DOG ADOPTION - ADMIN NOTIFICATION
🔔 New Adoption Application
Requires Your Review and Decision
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

A new adoption application has been submitted for <?= $dogName ?> and is
ready for your review.


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
👤 APPLICANT INFORMATION
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Name:       <?= $applicantName ?>

Email:      <?= $applicantEmail ?>

Phone:      <?= $applicantPhone ?>

Submitted:  <?= $application->dateCreated->format('F j, Y \a\t g:i A') ?>



━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
🏠 HOUSING & QUALIFICATIONS
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Housing Type:           <?= $housingType ?>

Has Children:           <?= $hasChildren ? 'Yes' : 'No' ?>

Owned Dogs Before:      <?= $everOwnedDogs ? 'Yes' : 'No' ?>

Primary Caretaker:      <?= $primaryCareTaker ? 'Yes' : 'No' ?>

Pickup Method:          <?= $application->pickup_method->name ?>



━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
⚡ ACTION REQUIRED
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Please review this application and make a decision. The applicant is
waiting to hear back within 3-5 business days.

👉 Review Application:
   http://localhost/dog-application/admin-dashboard


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

This is an automated notification from the Hannah's Haus adoption
system. Please do not reply to this email.


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Hannah's Haus Dog Adoption
Admin Dashboard

© <?= date('Y') ?> Hannah's Haus. All rights reserved.
