<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Country $foundCountries
 * @var \App\Model\Entity\HousingType $foundHousingTypes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?php if ($authUser) {
                echo $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']);
            }?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <!-- Name -->
                <div class="row">
                    <div class="column-responsive column-50">
                        <?php
                            echo $this->Form->control(
                                'fname',
                                ["label" => "First name"]
                            );
                        ?>
                    </div>
                    <div class="column-responsive column-50">
                        <?php
                            echo $this->Form->control(
                                'lname',
                                ["label" => "Last name"]
                            );
                        ?>
                    </div>
                </div>

                <!-- email, phone --> 
                <div class="row">
                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control('email', ["required" => true]);
                        ?>
                    </div>

                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control('phoneNumber');
                        ?>
                    </div>
                </div>

                <!-- password --> 
                <div class="row">
                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control('password', ["required" => true]);
                        ?>
                    </div>

                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control('confirmPassword', ['type' => "password"]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="column-responsive column-50">
                        <div class="column-responsive column-100">
                            <span style="color:red"
                                id="lengthCheck"
                                class="glyphicon glyphicon-remove">
                            </span>
                            <span>Has more than 8 characters</span>
                        </div>

                        <div class="column-responsive column-100">
                            <span style="color:red"
                                id="upperCheck"
                                class="glyphicon glyphicon-remove">
                            </span>
                            <span>Has uppercase</span>
                        </div>

                        <div class="column-responsive column-100">
                            <span style="color:red"
                                id="lowerCheck"
                                class="glyphicon glyphicon-remove">
                            </span>
                            <span>Has lowercase</span>
                        </div>

                        <div class="column-responsive column-100">
                            <span style="color:red"
                                id="numberCheck"
                                class="glyphicon glyphicon-remove">
                            </span>
                            <span>Has a number</span>
                        </div>

                        <div class="column-responsive column-100">
                            <span style="color:red"
                                id="specialCheck"
                                class="glyphicon glyphicon-remove">
                            </span>
                            <span>Has special character</span>
                        </div>

                    </div>
                </div>
                
                <!-- country, state -->
                <div class="row">
                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control(
                                'countryId',
                                [
                                    "label" => "Country",
                                    "type" => "select",
                                    "options" => $foundCountries,
                                    "empty" => "(Select a country)"
                                ]
                            );
                        ?>
                    </div>
                    
                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control(
                                'stateId',
                                [
                                    "label" => "State/Province",
                                    "type" => "select",
                                ]
                            );
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control(
                                'housingTypeId',
                                [
                                    "label" => "Housing Type",
                                    "type" => "select",
                                    "options" => $foundHousingTypes,
                                    "empty" => "(Select a housing type)"
                                ]
                            );
                        ?>
                    </div>
                </div>

                <!-- address --> 
                <div class="row">
                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control(
                                'address1',
                                ["label" => "Address"]
                            );
                        ?>
                    </div>

                    <div class="column-responsive column-50">
                        <?php 
                            echo $this->Form->control(
                                'address2',
                                ["label" => "Suite/Apt #"]
                            );
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="column-responsive column-50">
                        <?php
                            echo $this->Form->control('zipcode');
                        ?>
                    </div>
                </div>
                
                <?php
                    echo $this->Form->control(
                        'hasChildren',
                        [
                            "label" => " Do you have children?",
                            "options" => [
                                ["value" => 1, "text" => "Yes"],
                                ["value" => 0, "text" => "No"]
                            ],
                            "type" => "radio"
                        ]
                    );
                    echo $this->Form->control(
                        'everOwnedDogs',
                        [
                            "label" => "Have you ever owned a dog?",
                            "options" => [
                                ["value" => 1, "text" => "Yes"],
                                ["value" => 0, "text" => "No"]
                            ],
                            "type" => "radio"
                        ]
                    );
                    echo $this->Form->control(
                        'primaryCareTaker',
                        [
                            "label" => "Will You be the primary care taker of the dog(s) applied to?",
                            "options" => [
                                ["value" => 1, "text" => "Yes"],
                                ["value" => 0, "text" => "No"]
                            ],
                            "type" => "radio"
                        ]
                    );
                    //need to add a check to the below to ensure the end user applying to the site doesn't have acces to it
                    // echo $this->Form->control('isAdmin');

                    //also add dateadded and date modified to before save
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/Users/sharedUsersFunc.js"></script>
<script type="text/javascript" src="/js/Users/add.js"></script>
