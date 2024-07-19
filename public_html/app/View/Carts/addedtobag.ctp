<style type="text/css">
    a.btn.btn-primary {
        color: #ffffff;
        background-color: #333333;
        border-color: #333333;
    }

    .pdp-viewbag {
        max-width: 1200px;
        margin: auto;
        margin-top: 35px;
        padding: 8px 0;
        border: 1px solid #090909;
        position: relative;
    }

    .back-page {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 0;
        cursor: pointer;
        text-transform: inherit;
        color: #8b8a8a;
    }

    .top-breadcrumb {
        position: absolute;
        top: -44px;
        width: 100%;
        left: -15px;
        z-index: 1;
    }

    .pdp-viewbag .img-container {
        float: left;
        width: 88px;
        height: 120px;
        margin-right: 58px;
    }

    .pdp-viewbag .added-viewbag {
        position: absolute;
        left: 131px;
        color: #489a11;
        text-transform: uppercase;
        font-size: 18px;
    }

    .pdp-viewbag .tick-viewbag {
        width: 21px;
        height: 21px;
        display: inline-block;
        background-position: -246px -318px;
        margin-right: 10px;
    }

    .pdp-viewbag .product-info {
        margin-top: 40px;
        float: left;
        max-width: 391px;
    }

    .pdp-viewbag .product-info .head {
        color: #7e7e7e;
        font-size: 16px;
    }

    .pdp-viewbag .product-info .head .brand-name {
        color: #434343;
    }

    .pdp-viewbag .left {
        border-left: 1px solid #dedede;
        margin-left: 30px;
        padding-left: 50px;
        margin-top: 6px;
        color: #333;
    }

    .pdp-viewbag .left .viewbag {
        font-size: 16px;
    }

    .pdp-viewbag .viewbag-minicart .subtotal {
        font-size: 17px;
        margin: 4px 0 8px;
    }

    .pdp-viewbag .viewbag-minicart .view-bag {
        font-size: 22px;
    }

    .pdp-viewbag .product-info .price-actual {
        font-size: 18px;
        display: inline-block;
        font-family: dinnextprobold;
    }

    .pdp-viewbag .product-info .strike-through {
        text-decoration: line-through;
        font-size: 15px;
        color: #999;
        margin-right: 10px;
    }

    .pdp-viewbag .product-info .strike-through {
        text-decoration: line-through;
        font-size: 15px;
        color: #999;
        margin-right: 10px;
    }

    h2.title-border {
        width: 180px;
        margin: 20px auto;
        text-transform: uppercase;
        color: #434343;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px
    }

    @media (max-width: 768px) {
        .viewbag_top {
            clear: both;
        }

        .pdp-viewbag .left {
            padding: 0;
            margin: 0;
            border: none;
        }

        .pdp-viewbag .product-info {
            margin-top: 0;
        }
    }

    .img-thumbnail {
        min-height: 235px;
    }
</style>

<section class="section  mycontainer" style="margin-top: 20px;">
    <div class="pdp-viewbag row" style="margin-bottom: 35px;">
        <div class="item-container">
            <div class="container">
                <div class="col-md-12">

                <span class="top-breadcrumb">
                    <a href="<?php echo $this->Html->url(array('controller' => 'Packages', 'action' => 'index')) ?>"
                       class="back-page">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i> Back to Exams</a>
                </span>
                    <?php foreach ($products as $product):
                        $Package_ids = $product['Package']['id'];
                        if (strlen($product['Package']['photo']) > 0) {
                            $photo = "package_thumb/" . $product['Package']['photo'];
                        } else {
                            $photo = "nia.png";
                        }
                        if ($id == $Package_ids) {


                            ?>
                            <div class="col-md-7 -12 col-sm-12">
                                <div class="img-container">
                                    <?php echo $this->Html->image($photo, array('alt' => h($product['Package']['name']), 'class' => 'img-responsive')); ?>
                                </div>
                                <div class="added-viewbag">
                                    <i class="fa fa-check" aria-hidden="true"></i> Item Successfully Added to Cart
                                </div>
                                <div class="product-info">
                                    <div class="head"><span
                                                class="brand-name"><?php echo h($product['Package']['name']); ?></span>
                                    </div>
                                    <!-- div class="items-stock">
                                        <span>Size <small class="size">M</small></span>
                                         <span class="size-separator">|</span>
                                         <span>Qty <small class="qty">1</small></span>
                                     </div> -->
                                    <div class="item-price">
                                        <?php if ($product['Package']['show_amount'] != $product['Package']['amount']) { ?>
                                            <strike><span class="strike-through" style="font-weight: normal;">
                                            <span class="standard-price">
                                            <?php echo $currency . $product['Package']['show_amount']; ?></span></span>
                                            </strike><?php } ?> <span
                                                class="price-actual"><?php echo $currency . $product['Package']['count'] * $product['Package']['amount']; ?></span>
                                        <!-- <span class="strike-through">
                                            <span class="standard-price"> 3499</span>
                                        </span>
                                            <span class="price-actual"><span class="standard-price">2275</span></span> -->
                                    </div>
                                </div>
                            </div>
                        <?php }
                        $total = $total + ($product['Package']['count'] * $product['Package']['amount']);
                        $totalQuantity = $totalQuantity + $product['Package']['count'];
                    endforeach; ?>
                    <div class="viewbag_top"></div>
                    <div class="col-md-5 viewbag-minicart col-sm-12">
                        <div class="left">
                            <div class="viewbag">
                                <span class="bag">Your Cart </span>
                                <span class="items">(<?php echo $totalQuantity ?> Items)</span>
                            </div>
                            <div class="subtotal">
                                Cart Subtotal: <span>Rs.<?php echo $total; ?></span>
                            </div>
                            <div class="view-bag">
                                <a class="btn btn-primary"
                                   href="<?php echo $this->Html->url(array('controller' => 'Carts', 'action' => 'View')) ?>">View
                                    Cart</a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

</section>

<section class="section">
    <div class="page-heading">
        <div class="widget">
            <h2 class="title-border">featured exams</h2>
        </div>
    </div>
    <div class="container">
        <div class="flexslider carousel push-top mtt21">
            <ul class="slides">
                <?php //$Packages;
                foreach ($Packages as $Packagesvalue) {
                    $Package_id = $Packagesvalue['Package']['id'];
                    $Packagesvalue['Package']['name'];
                    $Packagesvalue['Package']['photo'];
                    if ($id != $Package_id) {
                        ?>
                        <li>
                            <div class="col-md-12">
                                <a href="<?php echo $this->Html->url(array('controller' => 'Packages', 'action' => 'singleproduct/' . $Package_id)) ?>">
                                    <div class="img-thumbnail">
                                        <?php if (strlen($Packagesvalue['Package']['photo']) > 0) {
                                            $photo1 = "package/" . $Packagesvalue['Package']['photo'];
                                        } else {
                                            $photo1 = "nia.png";
                                        } ?>
                                        <?php echo $this->Html->image($photo1, array('alt' => $post['Package']['name'])); ?>
                                    </div>
                                    <div style="clear: both;"></div>
                                    <h4 class="text-info text-center"><?php echo $Packagesvalue['Package']['name']; ?></h4>
                                </a>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(document).ready(function () {
        $('.flexslider').flexslider({
            animation: "slide",
            animationLoop: false,
            itemWidth: 241,
            itemMargin: 5,
            minItems: 2,
            maxItems: 4,
        });
    });


</script>