<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'Hannah\'s Haus : ';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" 
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
        crossorigin="anonymous"
    >
</head>

<body>
    <div>
        <div class="top-nav-title">
            <b><h4 style="margin: 0rem; background-color: #818d97;">Welcome to Hannah's Haus and Farm</h4></b>
        </div>
    </div>
    <div style="background-color:#8FACC0; padding: 1rem;">
        <nav class="top-nav">
            <div class="top-nav-links">
            <?= $this->Html->link(__('Home'), ['controller' => '/', 'action' => 'index']) ?>
                <?= $this->Html->link(__('Dog Profiles'), ['controller' => 'dogs', 'action' => 'index']) ?>
                <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Gallery</a>
                <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Policies</a>
                <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Privacy</a>
            </div>
            <div class="top-nav-links">
                <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Apply</a>
            </div>
        </nav>
    </div>
    <div style="background-color: #D2E4F1; padding: 1rem; min-height: 100vh; height: 100%;">
        <main class="main">
            <div class="container">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </main>
    </div>
</body>
<footer style="background-color:#818d97;">
<p>test</p>
</footer>

</html>