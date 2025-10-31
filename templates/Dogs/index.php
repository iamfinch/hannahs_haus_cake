<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Dog> $dogs
 */
?>
<div class="dogs index content">
    <?= $this->Html->link(__('New Dog'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Dogs') ?></h3>

    <div class="row">
        <?php foreach ($dogs as $dog): ?>
            <!-- <tr>
                <td><?= $this->Number->format($dog->id) ?></td>
                <td><?= h($dog->name) ?></td>
                <td><?= h($dog->dateBorn) ?></td>
                <td><?= h($dog->color) ?></td>
                <td><?= h($dog->retired) ?></td>
                <td><?= h($dog->retiredDate) ?></td>
                <td><?= h($dog->adopted) ?></td>
                <td><?= h($dog->adoptedDate) ?></td>
                <td><?= $dog->userId === null ? '' : $this->Number->format($dog->userId) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $dog->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dog->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dog->id)]) ?>
                </td>
            </tr> -->

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="padding12 margin12 card">
                    <div class="card-image">
                        image
                    </div>

                    <div class="card-title padding12">
                        <h4><?= h($dog->name) ?></h4>
                    </div>

                    <div class="padding12">
                        <hr />
                    </div>

                    <div class="card-desc-short padding12">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pretium in lectus eu tristique. Nam interdum, lorem eleifend tincidunt vehicula, erat urna consequat felis, et imperdiet velit quam in leo. Etiam tempus leo id justo cursus, in imperdiet nisl fermentum. Pellentesque hendrerit magna varius enim aliquet fermentum ac quis sapien. In vehicula lacus sed enim suscipit tempor. Proin eu vestibulum quam. Vivamus nunc eros, vestibulum eget fermentum id, tristique sit amet nisl. Quisque scelerisque, ligula non tristique aliquam, dui dui venenatis orci, quis rutrum lacus erat placerat lectus. Sed at...
                        </p>
                    </div>

                    <div class="card-button padding12">
                        <?= $this->Html->link(
                            __('Dogs'),
                            [ 'controller' => 'dogs', 'action' => 'index'],
                            [
                                'class' => 'btn btn-outline-info buttons'
                            ]
                        )?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th><?= $this->Paginator->sort('dateBorn') ?></th>
                    <th><?= $this->Paginator->sort('color') ?></th>
                    <th><?= $this->Paginator->sort('retired') ?></th>
                    <th><?= $this->Paginator->sort('retiredDate') ?></th>
                    <th><?= $this->Paginator->sort('adopted') ?></th>
                    <th><?= $this->Paginator->sort('adoptedDate') ?></th>

                    <?php if (isset($user) &&!empty($user))  { ?>
                        <th><?= $this->Paginator->sort('userId') ?></th>
                    <?php } ?>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dogs as $dog): ?>
                <tr>
                    <td><?= $this->Number->format($dog->id) ?></td>
                    <td><?= h($dog->name) ?></td>
                    <td><?= h($dog->dateBorn) ?></td>
                    <td><?= h($dog->color) ?></td>
                    <td><?= h($dog->retired) ?></td>
                    <td><?= h($dog->retiredDate) ?></td>
                    <td><?= h($dog->adopted) ?></td>
                    <td><?= h($dog->adoptedDate) ?></td>
                    <?php if (isset($user) &&!empty($user))  { ?>
                        <td><?= $dog->userId === null ? '' : $this->Number->format($dog->userId) ?></td>
                    <?php } ?>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $dog->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $dog->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $dog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $dog->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
