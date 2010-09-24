<?php echo $javascript->codeBlock(); ?>
    $.getJSON("http://api.flickr.com/services/feeds/photoset.gne?set=72157624650846249&nsid=20486061@N08&lang=en-us&format=json&jsoncallback=?",
        function(data) {
            $(data.items).each(
                function() {
                    $("#flicker_images").append('<li><div class="clipwrapper_flicker"><div class="clip_flicker"><a href="'+this.media.m+'" rel="facebox"><img src='+this.media.m+' alt="'+this.title+'" /></a></div></div></li>');
                }
            );
            $('#flicker_images a').facebox();
            $('#flicker_images').jcarousel();
        }
    );
    $('#plaza_suenos_menu').corner("bottom");
<?php echo $javascript->blockEnd(); ?>
<div id="plaza_suenos_menu">
    <div class="title"><?php echo __('Look what the kids are dreaming', true); ?></div>
    <ul id="flicker_images" class="jcarousel-skin-tango"></ul>
</div>
