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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $dog->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $dog->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Dogs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dogs form content">
            <?= $this->Form->create($dog) ?>
            <fieldset>
                <legend><?= __('Edit Dog') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('dateBorn');
                    echo $this->Form->control('color');
                    echo $this->Form->control('retired');
                    echo $this->Form->control('retiredDate', ['empty' => true]);
                    echo $this->Form->control('adopted');
                    echo $this->Form->control('adoptedDate', ['empty' => true]);
                    echo $this->Form->control('userId');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
