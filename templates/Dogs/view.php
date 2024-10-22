<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dog $dog
 */
?>
<?php
$isAdmin = $this->request->getSession()->read("User.isAdmin");
$button = $isAdmin ? $this->Html->link(
    'View Applications',
    "/dogApplication/view/{$dog->id}",
    ['class' => 'button', 'target' => '_blank']
) : $this->Html->link(
    'Apply to Adopt!',
    "/dogApplication/add",
    ['class' => 'button', 'target' => '_blank']
);
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
        
    </div>
</div>
