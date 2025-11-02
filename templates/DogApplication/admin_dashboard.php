<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\DogApplication> $pendingApplications
 * @var int $totalPending
 * @var int $totalApproved
 * @var int $totalRejected
 */
?>
<div class="dogApplication adminDashboard content">
    <h2><?= __('Admin Dashboard - Adoption Applications') ?></h2>

    <!-- Statistics Cards -->
    <div class="statistics-cards">
        <div class="stat-card stat-pending">
            <div class="stat-number"><?= h($totalPending) ?></div>
            <div class="stat-label"><?= __('Pending Review') ?></div>
        </div>
        <div class="stat-card stat-approved">
            <div class="stat-number"><?= h($totalApproved) ?></div>
            <div class="stat-label"><?= __('Approved') ?></div>
        </div>
        <div class="stat-card stat-rejected">
            <div class="stat-number"><?= h($totalRejected) ?></div>
            <div class="stat-label"><?= __('Rejected') ?></div>
        </div>
    </div>

    <h3><?= __('Pending Applications') ?></h3>

    <?php if ($pendingApplications->count() === 0): ?>
        <div class="message success">
            <p><?= __('All caught up! There are no pending applications to review.') ?></p>
        </div>
    <?php else: ?>
        <div class="applications-list">
            <?php foreach ($pendingApplications as $application): ?>
                <div class="application-card">
                    <!-- Application Header -->
                    <div class="app-header">
                        <div class="app-dog-info">
                            <h4><?= h($application->dog->name) ?></h4>
                            <span class="app-date">Applied: <?= h($application->dateCreated->format('M d, Y')) ?></span>
                        </div>
                        <div class="app-actions">
                            <?= $this->Form->postLink(
                                __('✓ Approve'),
                                ['action' => 'approve', $application->id],
                                [
                                    'confirm' => __('Approve application from {0} for {1}?',
                                        $application->user->fname . ' ' . $application->user->lname,
                                        $application->dog->name
                                    ),
                                    'class' => 'button button-approve'
                                ]
                            ) ?>
                            <?= $this->Form->postLink(
                                __('✗ Reject'),
                                ['action' => 'reject', $application->id],
                                [
                                    'confirm' => __('Reject application from {0} for {1}?',
                                        $application->user->fname . ' ' . $application->user->lname,
                                        $application->dog->name
                                    ),
                                    'class' => 'button button-reject'
                                ]
                            ) ?>
                            <?= $this->Html->link(
                                __('View Details'),
                                ['action' => 'view', $application->id],
                                ['class' => 'button button-secondary']
                            ) ?>
                        </div>
                    </div>

                    <!-- Applicant Information -->
                    <div class="app-content">
                        <div class="app-section">
                            <h5><?= __('Applicant Information') ?></h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label"><?= __('Name:') ?></span>
                                    <span class="info-value"><?= h($application->user->fname . ' ' . $application->user->lname) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Email:') ?></span>
                                    <span class="info-value"><?= h($application->user->email) ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Phone:') ?></span>
                                    <span class="info-value"><?= h($application->user->phoneNumber ?? 'Not provided') ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Pickup Method:') ?></span>
                                    <span class="info-value"><?= h($application->pickup_method->name) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Housing & Living Conditions -->
                        <div class="app-section">
                            <h5><?= __('Housing & Living Conditions') ?></h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label"><?= __('Housing Type:') ?></span>
                                    <span class="info-value"><?= h($application->user->housing_type->name ?? 'Not specified') ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Location:') ?></span>
                                    <span class="info-value">
                                        <?= h($application->user->address1) ?>
                                        <?php if ($application->user->address2): ?>
                                            , <?= h($application->user->address2) ?>
                                        <?php endif; ?>
                                        <br>
                                        <?= h($application->user->state->name ?? '') ?> <?= h($application->user->zipcode) ?>
                                        <?php if (isset($application->user->country)): ?>
                                            , <?= h($application->user->country->name) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Has Children:') ?></span>
                                    <span class="info-value <?= $application->user->hasChildren ? 'badge-yes' : 'badge-no' ?>">
                                        <?= $application->user->hasChildren ? __('Yes') : __('No') ?>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Previous Dog Owner:') ?></span>
                                    <span class="info-value <?= $application->user->everOwnedDogs ? 'badge-yes' : 'badge-no' ?>">
                                        <?= $application->user->everOwnedDogs ? __('Yes') : __('No') ?>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Primary Caretaker:') ?></span>
                                    <span class="info-value <?= $application->user->primaryCareTaker ? 'badge-yes' : 'badge-no' ?>">
                                        <?= $application->user->primaryCareTaker ? __('Yes') : __('No') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Statistics Cards */
.statistics-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
    border-left: 4px solid #ddd;
}

.stat-card.stat-pending {
    border-left-color: #ffc107;
}

.stat-card.stat-approved {
    border-left-color: #28a745;
}

.stat-card.stat-rejected {
    border-left-color: #dc3545;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #333;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Application Cards */
.applications-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.application-card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 1px solid #e0e0e0;
}

.app-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.app-dog-info h4 {
    margin: 0 0 0.25rem 0;
    color: #333;
    font-size: 1.25rem;
}

.app-date {
    font-size: 0.875rem;
    color: #666;
}

.app-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.button {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    display: inline-block;
    transition: all 0.2s;
}

.button-approve {
    background-color: #28a745;
    color: white;
}

.button-approve:hover {
    background-color: #218838;
}

.button-reject {
    background-color: #dc3545;
    color: white;
}

.button-reject:hover {
    background-color: #c82333;
}

.button-secondary {
    background-color: #6c757d;
    color: white;
}

.button-secondary:hover {
    background-color: #5a6268;
}

.app-content {
    padding: 1.5rem;
}

.app-section {
    margin-bottom: 1.5rem;
}

.app-section:last-child {
    margin-bottom: 0;
}

.app-section h5 {
    margin: 0 0 1rem 0;
    font-size: 1rem;
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 600;
}

.info-value {
    font-size: 0.95rem;
    color: #212529;
}

.badge-yes, .badge-no {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-weight: 600;
    width: fit-content;
}

.badge-yes {
    background-color: #d4edda;
    color: #155724;
}

.badge-no {
    background-color: #f8d7da;
    color: #721c24;
}

.message.success {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
    padding: 1rem 1.5rem;
    border-radius: 0.25rem;
    color: #155724;
}

/* Responsive Design */
@media (max-width: 768px) {
    .statistics-cards {
        grid-template-columns: 1fr;
    }

    .app-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .app-actions {
        width: 100%;
        justify-content: flex-start;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
