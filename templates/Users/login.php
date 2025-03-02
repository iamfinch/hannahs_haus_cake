<div class="users form content">
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email and password') ?></legend>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
    <?= $this->Html->link(__('Sign up!'), ['controller' => 'users', 'action' => 'add']) ?>
    <?= $this->Form->button(__('Login')); ?>
    <?= $this->Form->end() ?>
</div>