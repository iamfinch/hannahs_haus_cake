<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dog $dog
 */
?>
<div class="row">
    <div class="column-responsive column-100">
        <div class="dogs view content">
            <div class="row">
                <div class="column-responsive column-70">
                    image courosel
                </div>

                <div class="column-responsive column-30">
                    <h3><?= h($dog->name) ?></h3>

                    <table>
                        <tr>
                            <th><?= __('Color') ?></th>
                            <td><?= h($dog->color) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('DateBorn') ?></th>
                            <td><?= h($dog->dateBorn) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Adopted') ?></th>
                            <td><?= $dog->adopted ? __('Yes') : __('No'); ?></td>
                        </tr>
                        <?php if ($dog->adopted && !empty($dog->adoptedDate)) {?>
                            <tr>
                                <th><?= __('AdoptedDate') ?></th>
                                <td><?= $dog->adoptedDate ? h($dog->adoptedDate) : ''?></td>
                            </tr>
                        <?php }?>
                        <tr>
                            <th><?= __('Retired') ?></th>
                            <td><?= $dog->retired ? __('Yes') : __('No'); ?></td>
                        </tr>
                        <?php if ($dog->retired && !empty($dog->retiredDate)) {?>
                            <tr>
                                <th><?= __('RetiredDate') ?></th>
                                <td><?= h($dog->retiredDate) ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <?php if (!empty($dog->about)) {?>
                <div class="row">
                    <div class="column-responsive column-100">
                        <?= $dog->about?>
                    </div>
                </div>
            <?php } ?>

            <div class="row">
                <div class="column-responsive column-30">
                    
                </div>

                <div class="column-responsive column-70">
                    
                </div>
            </div>
        </div>
    </div>
</div>
