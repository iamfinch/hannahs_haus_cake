<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\DogApplication> $applications
 */
?>
<div class="dogApplication myApplications content">
    <h3><?= __('My Adoption Applications') ?></h3>

    <?php if ($applications->count() === 0): ?>
        <div class="message">
            <p><?= __('You have not submitted any adoption applications yet.') ?></p>
            <p><?= $this->Html->link(__('Browse Available Dogs'), ['controller' => 'Dogs', 'action' => 'index'], ['class' => 'button']) ?></p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th><?= __('Dog') ?></th>
                        <th><?= __('Applied Date') ?></th>
                        <th><?= __('Pickup Method') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Status Date') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                    <tr>
                        <td>
                            <?= $this->Html->link(
                                h($application->dog->name),
                                ['controller' => 'Dogs', 'action' => 'view', $application->dog->id]
                            ) ?>
                        </td>
                        <td><?= h($application->dateCreated->format('M d, Y')) ?></td>
                        <td><?= h($application->pickup_method->name) ?></td>
                        <td>
                            <?php
                            $statusClass = '';
                            $statusText = '';
                            switch ($application->approved) {
                                case '1':
                                    $statusClass = 'success';
                                    $statusText = __('Approved');
                                    break;
                                case '-1':
                                    $statusClass = 'error';
                                    $statusText = __('Rejected');
                                    break;
                                case '0':
                                default:
                                    $statusClass = 'warning';
                                    $statusText = __('Pending');
                                    break;
                            }
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td>
                            <?= $application->approvedDate ? h($application->approvedDate->format('M d, Y')) : '-' ?>
                        </td>
                        <td class="actions">
                            <?= $this->Html->link(__('View'), ['action' => 'view', $application->id]) ?>
                            <?php if ($application->approved === '0'): ?>
                                <?= $this->Form->postLink(
                                    __('Withdraw'),
                                    ['action' => 'delete', $application->id],
                                    ['confirm' => __('Are you sure you want to withdraw this application?')]
                                ) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <style>
            .status-badge {
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.875rem;
                font-weight: 600;
            }
            .status-badge.success {
                background-color: #d4edda;
                color: #155724;
            }
            .status-badge.error {
                background-color: #f8d7da;
                color: #721c24;
            }
            .status-badge.warning {
                background-color: #fff3cd;
                color: #856404;
            }
        </style>
    <?php endif; ?>
</div>
