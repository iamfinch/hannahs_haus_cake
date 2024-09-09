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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>
                <?php
                    echo $this->Form->control('email');
                    echo $this->Form->control('phoneNumber');
                    echo $this->Form->control('password');
                    echo $this->Form->control('fname');
                    echo $this->Form->control('lname');
                    echo $this->Form->control('countryId');
                    echo $this->Form->control('stateId');
                    echo $this->Form->control('zipcode');
                    echo $this->Form->control('housingTypeId');
                    echo $this->Form->control('hasChildren');
                    echo $this->Form->control('everOwnedDogs');
                    echo $this->Form->control('primaryCareTaker');
                    echo $this->Form->control('isAdmin');
                    echo $this->Form->control('dateCreated');
                    echo $this->Form->control('lastModified');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
