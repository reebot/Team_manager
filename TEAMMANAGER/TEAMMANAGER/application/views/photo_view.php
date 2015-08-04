<!-- start boxes -->
<div class="gradient-up-with-border pt30 pb20">
  <div class="full-width">
	<h2>Photos</h2>
		<?php foreach($this->photos as $photos): ?>
	      <div class="one-fourth">
	          <div class="outer-box"><div class="inner-box-filled-grey">
	              <h3><?php echo $photos->title ?></h3>
	              <div class="image-to-center"><a href="_include/images/misc/picture1.jpg" class="picture-frame-fifth mb0" rel="prettyPhoto[gallery]"><img src="<?php echo url("uploads/hallway.jpg" , true) ?>" width="194" height="113" alt="Alt Caption Trouble" class="captify" /><span class="small-plus"><!--  --></span></a></div>
	              <p>Maecenas purus libero, cursus ut dignissim in, cursus ut purus libero.</p>
	              <a href="#" class="text-link">Learn More</a>
	          </div></div>
	      </div>
		<?php endforeach ?>
			
			<div class="fl"><a href="<?php echo url("upload") ?>" class="btn-small-action" style="margin-right:0px;"><span>Add more</span></a></div>
			
			<div class="clear"></div>
			
		<div class="clear"></div>
  </div>
</div>
 <!-- end boxes -->