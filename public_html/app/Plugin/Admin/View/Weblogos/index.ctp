<div class="page-title">
	<div class="title-env"><h1 class="title"><?php echo __('Organisation Logo'); ?></h1></div>
</div>
<div class="panel">

	<div class="panel-body"> <?php echo $this->Session->flash(); ?>
		<div class="col-sm-6">
			<h1><?php echo __('Logo');?></h1>
			<?php echo $this->Form->create('Weblogo', array('name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal', 'type' => 'file')); ?>
			<div class="form-group">
				<label for="group_name" class="col-sm-4 control-label">
					<small><?php echo __('Upload Image(* height less than 220px)'); ?></small>
				</label>
				<div class="col-sm-8">
					<?php echo $this->Form->input('photo', array('required' => true, 'type' => 'file', 'label' => false, 'class' => 'form-horizontal', 'div' => false)); ?>
				</div>
			</div>
			<div class="form-group text-left">
				<div class="col-sm-offset-4 col-sm-8">
					<?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escape' => false)); ?>
					<?php echo $this->Html->link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete Logo'), array('controller' => 'Weblogos', 'action' => 'weblogodel'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
			<?php if (strlen($frontLogo) > 0) { echo $this->Html->image($frontLogo); }?>
		</div>
		<div class="col-sm-6">
			<h1><?php echo __('Favicon');?></h1>
			<?php echo $this->Form->create('Weblogo', array('url'=>array('action'=>'favicon'),'name' => 'post_req', 'id' => 'post_req', 'class' => 'form-horizontal', 'type' => 'file')); ?>
			<div class="form-group">
				<label for="group_name" class="col-sm-4 control-label">
					<small><?php echo __('PNG Image(* width & height less than 256px)'); ?></small>
				</label>
				<div class="col-sm-8">
					<?php echo $this->Form->input('favicon', array('required' => true, 'type' => 'file', 'label' => false, 'class' => 'form-horizontal', 'div' => false,"accept"=>"image/png")); ?>
				</div>
			</div>
			<div class="form-group text-left">
				<div class="col-sm-offset-4 col-sm-8">
					<?php echo $this->Form->button('<span class="fa fa-plus-circle"></span>&nbsp;' . __('Save'), array('class' => 'btn btn-success', 'escape' => false)); ?>
					<?php echo $this->Html->link('<span class="fa fa-trash"></span>&nbsp;' . __('Delete Favicon'), array('controller' => 'Weblogos', 'action' => 'favicondel'), array('escape' => false, 'class' => 'btn btn-danger')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
			<?php if (strlen($sysSetting['Configuration']['favicon']) > 0) { echo $this->Html->image($sysSetting['Configuration']['favicon']); }?>
		</div>
	</div>
</div>
