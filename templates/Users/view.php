<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New User'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users view content">
            <h3><?= h($user->fname) ?></h3>
            <table>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('PhoneNumber') ?></th>
                    <td><?= h($user->phoneNumber) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fname') ?></th>
                    <td><?= h($user->fname) ?></td>
                </tr>
                <tr>
                    <th><?= __('Lname') ?></th>
                    <td><?= h($user->lname) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('CountryId') ?></th>
                    <td><?= $this->Number->format($user->countryId) ?></td>
                </tr>
                <tr>
                    <th><?= __('StateId') ?></th>
                    <td><?= $this->Number->format($user->stateId) ?></td>
                </tr>
                <tr>
                    <th><?= __('Zipcode') ?></th>
                    <td><?= $this->Number->format($user->zipcode) ?></td>
                </tr>
                <tr>
                    <th><?= __('HousingTypeId') ?></th>
                    <td><?= $this->Number->format($user->housingTypeId) ?></td>
                </tr>
                <tr>
                    <th><?= __('DateCreated') ?></th>
                    <td><?= h($user->dateCreated) ?></td>
                </tr>
                <tr>
                    <th><?= __('LastModified') ?></th>
                    <td><?= h($user->lastModified) ?></td>
                </tr>
                <tr>
                    <th><?= __('HasChildren') ?></th>
                    <td><?= $user->hasChildren ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('EverOwnedDogs') ?></th>
                    <td><?= $user->everOwnedDogs ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('PrimaryCareTaker') ?></th>
                    <td><?= $user->primaryCareTaker ? __('Yes') : __('No'); ?></td>
                </tr>
                <tr>
                    <th><?= __('IsAdmin') ?></th>
                    <td><?= $user->isAdmin ? __('Yes') : __('No'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
