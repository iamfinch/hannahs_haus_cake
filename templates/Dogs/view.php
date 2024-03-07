<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dog $dog
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dog'), ['action' => 'edit', $dog->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dog'), ['action' => 'delete', $dog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dog->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dogs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dog'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dogs view content">
            <h3><?= h($dog->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($dog->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Color') ?></th>
                    <td><?= h($dog->color) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($dog->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('UserId') ?></th>
                    <td><?= $dog->userId === null ? '' : $this->Number->format($dog->userId) ?></td>
                </tr>
                <tr>
                    <th><?= __('DateBorn') ?></th>
                    <td><?= h($dog->dateBorn) ?></td>
                </tr>
                <tr>
                    <th><?= __('RetiredDate') ?></th>
                    <td><?= h($dog->retiredDate) ?></td>
                </tr>
                <tr>
                    <th><?= __('AdoptedDate') ?></th>
                    <td><?= h($dog->adoptedDate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Retired') ?></th>
                    <td><?= $dog->retired ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('Adopted') ?></th>
                    <td><?= $dog->adopted ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
