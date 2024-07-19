<section class="section">
    <div class="container">
        <div class="col-md-12 mycontainer">
            <div class="page-heading" style="padding-top: 100px;">
                <div class="widget">
                    <h2 class="title-border"><?php echo h($linkName1); ?> <span><?php echo h($linkName2); ?></span></h2>
                </div>
            </div>
            <div><?php echo str_replace("<script", "", $contentPost['Content']['main_content']); ?></div>
        </div>
    </div>
</section>
