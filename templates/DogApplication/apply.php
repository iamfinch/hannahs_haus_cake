<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DogApplication $application
 * @var \App\Model\Entity\Dog $dog
 * @var array $pickupMethods
 */
?>
<div class="dogApplication form content">
    <h3><?= __('Apply to Adopt: {0}', h($dog->name)) ?></h3>

    <div class="dog-summary">
        <h4><?= __('Dog Information') ?></h4>
        <p><strong><?= __('Name:') ?></strong> <?= h($dog->name) ?></p>
        <p><strong><?= __('Color:') ?></strong> <?= h($dog->color) ?></p>
        <p><strong><?= __('Born:') ?></strong> <?= h($dog->dateBorn->format('F j, Y')) ?></p>
        <?php if (!empty($dog->about)): ?>
            <p><strong><?= __('About:') ?></strong> <?= h($dog->about) ?></p>
        <?php endif; ?>
    </div>

    <?= $this->Form->create($application) ?>
    <fieldset>
        <legend><?= __('Application Details') ?></legend>

        <div class="form-group">
            <label><?= __('How would you like to receive your new companion?') ?></label>
            <?php foreach ($pickupMethods as $id => $method): ?>
                <div class="radio-option">
                    <?= $this->Form->radio('pickupMethodId', [
                        ['value' => $id, 'text' => $method]
                    ]) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="info-box">
            <h4><?= __('Next Steps') ?></h4>
            <ol>
                <li><?= __('Submit your application') ?></li>
                <li><?= __('Hannah\'s Haus will review within 2-3 business days') ?></li>
                <li><?= __('You\'ll receive email notification of approval/rejection') ?></li>
                <li><?= __('Track application status in "My Applications"') ?></li>
            </ol>
        </div>
    </fieldset>

    <div class="form-actions">
        <?= $this->Form->button(__('Submit Application'), ['class' => 'button-primary']) ?>
        <?= $this->Html->link(__('Cancel'), ['controller' => 'Dogs', 'action' => 'view', $dog->id], ['class' => 'button-secondary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<style>
.dogApplication.form {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
}

.dog-summary {
    background: #f5f5f5;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-radius: 8px;
    border-left: 4px solid #8FACC0;
}

.dog-summary h4 {
    margin-top: 0;
    color: #333;
}

.dog-summary p {
    margin: 0.5rem 0;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #333;
}

.radio-option {
    margin-bottom: 1rem;
    padding: 1rem;
    background: white;
    border: 2px solid #ddd;
    border-radius: 6px;
    transition: all 0.2s;
    cursor: pointer;
}

.radio-option:hover {
    background: #f9f9f9;
    border-color: #8FACC0;
}

.radio-option input[type="radio"] {
    margin-right: 0.75rem;
    cursor: pointer;
}

.radio-option label {
    font-weight: normal;
    cursor: pointer;
    margin: 0;
}

.info-box {
    background: #e3f2fd;
    padding: 1.5rem;
    margin-top: 2rem;
    border-left: 4px solid #2196f3;
    border-radius: 4px;
}

.info-box h4 {
    margin-top: 0;
    color: #1976d2;
}

.info-box ol {
    margin: 0;
    padding-left: 1.5rem;
}

.info-box li {
    margin-bottom: 0.5rem;
    color: #555;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #ddd;
}

.button-primary {
    background-color: #8FACC0;
    color: white;
    padding: 0.75rem 2rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s;
}

.button-primary:hover {
    background-color: #7a95ac;
}

.button-secondary {
    background-color: #f5f5f5;
    color: #333;
    padding: 0.75rem 2rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.2s;
}

.button-secondary:hover {
    background-color: #e0e0e0;
}

fieldset {
    border: none;
    padding: 0;
}

legend {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #8FACC0;
}
</style>
