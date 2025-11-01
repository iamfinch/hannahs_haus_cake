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

        <?= $this->Html->css([
            'normalize.min',
            'milligram.min',
            'fonts',
            'cake',
            'form-validation'
        ]) ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validation-unobtrusive/3.2.12/jquery.validate.unobtrusive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" 
            crossorigin="anonymous">
        </script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
            crossorigin="anonymous">
    </head>

    <body>
        <!-- <div>
            <div class="top-nav-title">
                <b><h4 style="margin: 0rem; background-color: #818d97;">Hannah's Haus and Farm</h4></b>
            </div>
        </div> -->

        <div style="background-color:#8FACC0; padding: 1rem;">
            <div class="top-nav-title">
                <b><h4>Hannah's Haus and Farm</h4></b>
            </div>
            <nav class="top-nav">
                <div class="top-nav-links">
                    <?= $this->Html->link(__('Home'), ['controller' => '/', 'action' => 'index']) ?>

                    <?= $this->Html->link(__('Dogs'), ['controller' => 'dogs', 'action' => 'index']) ?>

                    <?= $this->Html->link(__('Gallery'), ['controller' => 'pages', 'action' => 'gallery']) ?>

                    <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Policies</a>

                    <a target="_blank" rel="noopener" href="https://book.cakephp.org/4/">Privacy</a>
                </div>

                <div class="top-nav-links">
                    <?php if ($this->request->getAttribute('identity')) {?>
                        <?= $this->Html->link(__('Profile'), ['controller' => 'users', 'action' => 'profile']) ?>
                        <?= $this->Html->link(__('Logout'), ['controller' => 'users', 'action' => 'logout']) ?>
                    <?php } else { ?>
                        <?= $this->Html->link(__('Login'), ['controller' => 'users', 'action' => 'login']) ?>
                    <?php }?>
                </div>
            </nav>
        </div>

        <div style="background-color: ##F0F0F0; padding: 1rem; min-height: 100vh; height: 100%;">
            <main class="main">
                <div class="container">
                    <?= $this->Flash->render() ?>

                    <?= $this->fetch('content') ?>
                </div>
            </main>
        </div>
    </body>

    <footer style="background-color:#818d97; height: 180px;">
        <div class="container">
            <div class="row" style="padding: 12px 0px;">
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6" style="justify-content:right">
                    <?php echo $this->Html->image('cropped_company_logo.png', [
                        'alt' => 'Hannah\'s Haus & Farm Logo',
                        'class' => 'img-fluid w-50 d-block mx-auto LogoPicSize'
                    ]);?>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
                    <?= $this->Html->link(__('About'), ['controller' => 'pages', 'action' => 'about']) ?>

                    <?= $this->Html->link(__('Adoption Policy'), ['controller' => 'pages', 'action' => 'adoption_policy']) ?>

                    <?= $this->Html->link(__('Contact Us'), ['controller' => 'pages', 'action' => 'contact_us']) ?>

                    <?= $this->Html->link(__('Privacy Policy'), ['controller' => 'pages', 'action' => 'privacy_policy']) ?>
                </div>
            </div>
        </div>
    </footer>
</html>