<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dog $dog
 * @var bool $isAuthenticated
 * @var bool $hasPendingApplication
 */
?>
<?php
// Determine button state and content based on user authentication and dog status
$identity = $this->request->getAttribute('identity');
$isAdmin = $identity && $identity->get('isAdmin');

if ($isAdmin) {
    // Admins see "View Applications" button
    $button = $this->Html->link(
        'View Applications',
        ['controller' => 'DogApplication', 'action' => 'index', '?' => ['dogId' => $dog->id]],
        ['class' => 'button button-primary']
    );
} elseif ($dog->adopted) {
    // Dog is adopted - disabled button
    $button = '<button class="button button-disabled" disabled>Already Adopted</button>';
} elseif ($dog->retired) {
    // Dog is retired - disabled button
    $button = '<button class="button button-disabled" disabled>Not Available</button>';
} elseif (!$isAuthenticated) {
    // User not logged in - link to login with return URL
    $returnUrl = $this->Url->build(['controller' => 'Dogs', 'action' => 'view', $dog->id], ['fullBase' => false]);
    $button = $this->Html->link(
        'Login to Apply',
        ['controller' => 'Users', 'action' => 'login', '?' => ['redirect' => $returnUrl]],
        ['class' => 'button button-secondary']
    );
} elseif ($hasPendingApplication) {
    // User has pending application - disabled button with link to applications
    $button = '<button class="button button-warning" disabled>Application Pending</button> ' .
              $this->Html->link('View My Applications', ['controller' => 'DogApplication', 'action' => 'myApplications'], ['class' => 'button-link']);
} else {
    // User can apply - main CTA button
    $button = $this->Html->link(
        'Apply to Adopt!',
        ['controller' => 'DogApplication', 'action' => 'apply', $dog->id],
        ['class' => 'button button-primary button-large']
    );
}
?>
<div class="column">
    <!-- <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dog'), ['action' => 'edit', $dog->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dog'), ['action' => 'delete', $dog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dog->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dogs'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dog'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside> -->
    <div class="row dogs view content">
        <!-- Image Carousel-->
        <div>
            <?= $this->Breadcrumbs->render(
                ['class' => 'breadcrumbs-trail'],
                ['separator' => '<i class="fa fa-angle-right"></i>']
            );?>
        </div>

        <div class="column-responsive column-60" style="border: black 1px solid">
            <h3>Image Carousel</h3>
        </div>

        <!-- Dog Info -->
        <div class="column-responsive column-offset-10 column-30">
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
                    <th><?= __('DateBorn') ?></th>
                    <td><?= h($dog->dateBorn) ?></td>
                </tr>

                <?php if ($dog->retired && $dog->retiredDate) { ?>
                    <tr>
                        <th><?= __('RetiredDate') ?></th>
                        <td><?= h($dog->retiredDate) ?></td>
                    </tr>
                <?php } ?>

                <?php if ($dog->adopted && $dog->adoptedDate) { ?>
                    <tr>
                        <th><?= __('AdoptedDate') ?></th>
                        <td><?= h($dog->adoptedDate) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <?php if ($dog->about) { ?>
        <div class="row">
            <p><?= h($dog->about)?></p>
        </div>
    <?php } ?>

    <?php if ($dog->experiences || $dog->tags) {?>
        <div class="row">
            <?php if ($dog->tags) { ?>
                <p><?= h($dog->tags)?></p>
            <?php } ?>

            <?php if ($dog->experiences) { ?>
                <p><?= h($dog->experiences)?></p>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="adoption-action">
            <?= $button ?>
        </div>
    </div>
</div>

<style>
.adoption-action {
    margin: 2rem 0;
    padding: 2rem;
    text-align: center;
    background: #f5f5f5;
    border-radius: 8px;
}

.button {
    display: inline-block;
    padding: 0.75rem 2rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    font-size: 1rem;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.button-primary {
    background-color: #8FACC0;
    color: white;
}

.button-primary:hover {
    background-color: #7a95ac;
}

.button-primary.button-large {
    padding: 1rem 3rem;
    font-size: 1.25rem;
}

.button-secondary {
    background-color: #f5f5f5;
    color: #333;
    border: 2px solid #8FACC0;
}

.button-secondary:hover {
    background-color: #8FACC0;
    color: white;
}

.button-disabled {
    background-color: #e0e0e0;
    color: #999;
    cursor: not-allowed;
}

.button-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 2px solid #ffc107;
}

.button-link {
    display: inline-block;
    margin-left: 1rem;
    color: #8FACC0;
    text-decoration: underline;
}

.button-link:hover {
    color: #7a95ac;
}
</style>
