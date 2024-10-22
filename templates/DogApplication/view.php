<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DogApplication $dogApplication
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dog Application'), ['action' => 'edit', $dogApplication->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dog Application'), ['action' => 'delete', $dogApplication->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dogApplication->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dog Application'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dog Application'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dogApplication view content">
            <h3><?= h($dogApplication->approved) ?></h3>
            <table>
                <tr>
                    <th><?= __('Approved') ?></th>
                    <td><?= h($dogApplication->approved) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($dogApplication->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('UserId') ?></th>
                    <td><?= $this->Number->format($dogApplication->userId) ?></td>
                </tr>
                <tr>
                    <th><?= __('DogId') ?></th>
                    <td><?= $this->Number->format($dogApplication->dogId) ?></td>
                </tr>
                <tr>
                    <th><?= __('PickupMethodId') ?></th>
                    <td><?= $this->Number->format($dogApplication->pickupMethodId) ?></td>
                </tr>
                <tr>
                    <th><?= __('DateCreated') ?></th>
                    <td><?= h($dogApplication->dateCreated) ?></td>
                </tr>
                <tr>
                    <th><?= __('ApprovedDate') ?></th>
                    <td><?= h($dogApplication->approvedDate) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
