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
            <?= $this->Html->link(__('List Dog Application'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dogApplication form content">
            <?= $this->Form->create($dogApplication) ?>
            <fieldset>
                <legend><?= __('Add Dog Application') ?></legend>
                <?php
                    echo $this->Form->control('userId');
                    echo $this->Form->control('dogId');
                    echo $this->Form->control('pickupMethodId');
                    echo $this->Form->control('dateCreated');
                    echo $this->Form->control('approved');
                    echo $this->Form->control('approvedDate', ['empty' => true]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
