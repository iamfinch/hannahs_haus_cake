<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\DogApplication> $dogApplication
 */
?>
<div class="dogApplication index content">
    <?= $this->Html->link(__('New Dog Application'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Dog Application') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('userId') ?></th>
                    <th><?= $this->Paginator->sort('dogId') ?></th>
                    <th><?= $this->Paginator->sort('pickupMethodId') ?></th>
                    <th><?= $this->Paginator->sort('dateCreated') ?></th>
                    <th><?= $this->Paginator->sort('approved') ?></th>
                    <th><?= $this->Paginator->sort('approvedDate') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dogApplication as $dogApplication): ?>
                <tr>
                    <td><?= $this->Number->format($dogApplication->id) ?></td>
                    <td><?= $this->Number->format($dogApplication->userId) ?></td>
                    <td><?= $this->Number->format($dogApplication->dogId) ?></td>
                    <td><?= $this->Number->format($dogApplication->pickupMethodId) ?></td>
                    <td><?= h($dogApplication->dateCreated) ?></td>
                    <td><?= h($dogApplication->approved) ?></td>
                    <td><?= h($dogApplication->approvedDate) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $dogApplication->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dogApplication->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dogApplication->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dogApplication->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
