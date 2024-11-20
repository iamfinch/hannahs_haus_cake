<div class="row" style="padding: 15px 0px">
    <div class="col-sm-12">
        <div id="FeaturedImagesCarousel" class="carousel slide row" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner col-12">
                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar1.jpg', [
                                'alt' => 'Dogs outside',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar2.jpg', [
                                'alt' => 'Puppy outside',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar3.jpg', [
                                'alt' => 'Our Stud in the snow',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar4.jpg', [
                                'alt' => 'Puppy on a couch',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar5.2.jpg', [
                                'alt' => 'Chloe & Zeke in a bed',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar6.jpg', [
                                'alt' => 'Dogs outside',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar7.jpg', [
                                'alt' => 'Puppy on a couch',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar8.jpg', [
                                'alt' => 'Giant outside',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo $this->Html->image('homecar9.jpg', [
                                'alt' => 'Dogs outside',
                                'class' => 'img-fluid CarouselPicSize d-block mx-auto'
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#FeaturedImagesCarousel" data-bs-slide="prev">
                <span style="color: white" class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#FeaturedImagesCarousel" data-bs-slide="next">
                <span style="color: white" class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</div>

<div class="row" style="padding: 15px 0px">
    <div id="HomePageTopLeft" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2>About the Breeder</h2>

        <h5>
            I'm the breeder behind Hannah's Haus & Farm located in Atoka, OK. My husband, I, our dogs live on our 40 acre farm.
            Our dogs are very loved and well-socialized. I strive to make sure every owner has the ability to get the very best out of their puppy.
            Our dogs live as part of our family in our home. The puppies will too until they go to their forever home.
        </h5>

        <div id="LearnMore">
            <?= $this->Html->link(
                __('Dogs'),
                [ 'controller' => 'dogs', 'action' => 'index'],
                [
                    'class' => 'btn btn-outline-success buttons',
                    'target' => '_blank'
                ]
            )?>
        </div>
    </div>
</div>

<hr />

<div class="row" style="padding: 15px 0px">
    <div id="HomePageTopLeft" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2>About the Breeder</h2>

        <h5>
            I'm the breeder behind Hannah's Haus & Farm located in Atoka, OK. My husband, I, our dogs live on our 40 acre farm.
            Our dogs are very loved and well-socialized. I strive to make sure every owner has the ability to get the very best out of their puppy.
            Our dogs live as part of our family in our home. The puppies will too until they go to their forever home.
        </h5>
        
        <div class="offset-lg-11 col-lg-1 offset-md-11 col-md-1 offset-sm-11 col-sm-1 offset-xs-11 col-xs-1">
            <div id="LearnMore">
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
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="padding12 margin12 card">
            <div class="card-image">
                image
            </div>

            <div class="card-title padding12">
                <h4>Name</h4>
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

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="padding12 margin12 card">
            <div class="card-image">
                image
            </div>

            <div class="card-title padding12">
                <h4>Name</h4>
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

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="padding12 margin12 card">
            <div class="card-image">
                image
            </div>

            <div class="card-title padding12">
                <h4>Name</h4>
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

    <div class="offset-lg-11 col-lg-1 offset-md-11 col-md-1 offset-sm-11 col-sm-1 offset-xs-11 col-xs-1">
        <?= $this->Html->link(
            __('View Dogs'),
            [ 'controller' => 'dogs', 'action' => 'index'],
            [
                'class' => 'btn btn-outline-info buttons',
                'target' => '_blank'
            ]
        )?>
    </div>
</div>

<style>
    .padding12 {
        padding: 12px;
    }
    .margin12 {
        margin: 12px;
    }
    .card{
        border: black solid 1px;
        border-radius: 5px;
        padding: 0px
    }
    .card-image {
        height:100px;
        width: 100%;
        border: black solid 1px;
        border-radius: 5px 5px 0px 0px;
    }
    .card-desc-short {
        overflow-y: scroll;
        max-height:200px;
    }
    .card-button {
        text-align: right;
    }
</style>