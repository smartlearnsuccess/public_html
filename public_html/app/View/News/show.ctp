<section class="section">
    <div class="container mycontainer ">
        <div class="col-md-12">
            <div class="page-heading">
                <div class="widget">
                    <h2 class="title-border"><?php echo h($newsPost['News']['news_title']); ?></h2>
                </div>
            </div>
            <?php echo $this->Session->flash(); ?>
            <div><?php echo str_replace("<script", "", ($newsPost['News']['news_desc'])); ?></div>
        </div>
    </div>
</section>
