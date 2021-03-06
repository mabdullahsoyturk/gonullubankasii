<?php
/**
  * @var \App\View\AppView $this
  */
?>
<?php $this->start('page_title'); ?>
<h1><?= h($event->title)  ?></h1>
<?php $this->end(); ?>

    <div class="events view large-9 medium-8 columns content">
        <div class="boxShadow padding-5">
            <div class="row">

    <?php if($belongsTo || $is_admin) :?>
              <nav class="navbar navbar-default">
  <div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <?php
    $editIcon = "<span class='glyphicon glyphicon-pencil'></span> ";
    $seeIcon = "<span class='glyphicon glyphicon-list'></span> ";
     ?>


    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><?= $this->Html->link($seeIcon .  __('See current applications'), '/event-applications/index/'.$event->id, ['escape'=>false]); ?></li>
        <li><?= $this->Html->link($editIcon . __('Edit the call'), ['controller'=>'events', 'action'=>'edit', $event->id], ['escape'=>false]); ?> </li>
        <?php
            if($is_admin && $event->approved == 0):
        ?>
        <li><?=$this->Html->link(__('Approve this event'), '/events/approve/'.$event->id, ['escape'=>false]); ?> </li>
        <?php
            endif;
            if($is_admin):
        ?>
        <li><?=$this->Html->link(__('Delete this event'), '/events/delete/'.$event->id, ['escape'=>false]); ?> </li>

        <?php endif;?>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php endif; ?>
            </div>
            <div class='row'>
              <div class='col-md-4'>
                <?= $this->Html->image('/files/Events/image/'.$event->image, array('border' => '0', 'style'=> 'width:100%')); ?>
              </div>
              <div class='col-md-8'>

                <h4 style='margin-top:0'><?= __('Why do we need volunteers?') ?></h4>
                <?php if(empty($event->description)):?>
                <?= __('No description is written.'); ?>
                <?php endif;?>
                <?= $this->Text->autoParagraph(h($event->description)); ?>
                <h4><?= __('What are our requirements?') ?></h4>
                <?php if(empty($event->specifications)): ?>
                <?= __('No requirement is specified.'); ?>
                <?php endif;?>
                <?= $this->Text->autoParagraph(h($event->specifications)); ?>
                <h4><?= __('How many volunteers do we need?') ?></h4>
                <?php if(empty($event->volunteer_count)):?>
                <?= __('No volunteer number is specified.'); ?>
                <?php endif;?>
                <?= $this->Text->autoParagraph(h($event->volunteer_count)); ?>
                <h4><?= __('Address') ?></h4>
                  <p>
                    <?= h($event->address); ?>
                  </p>
                <h4><?= __('When is deadline?'); ?></h4>
                 <p>
                  <?= $event->deadline ?>
                 </p>

                <hr>
                <h4 style='border:none'><?= __('Status');?>:</h4>
                <table class="table">
                  <tr>
                    <th><?= __('Call for') ?></th>
                    <th><?= __('Applied') ?></th>
                    <th><?= __('Approved') ?></th>
                    <th><?= __('Left') ?></th>
                  </tr>
                  <tr>
                    <td><?= $event->volunteer_number; ?></td>
                    <td><?= $event->total;?></td>
                    <td><?= $event->approved_applications; ?></td>
                    <td><?= $event->volunteer_number - $event->approved_applications; ?></td>
                  </tr>
                </table>

                <p>
                <?php if($applied == 'can_apply'): ?>
                  <a onclick="<?= $this->request->Session()->read('Auth.User') ? "$('#modal-form-new-needuser').modal('show')" : "window.location = '/login'"; ?>" title="<?= __('I want to apply'); ?>" class="btn btn-primary">
                    <span class="glyphicon glyphicon-ok"></span> <?= __('I want to apply'); ?>
                  </a>
                <?php elseif($applied == 'applied'): ?>
                   <button type="button" class="btn btn-success" data-toggle="dropdown" aria-haspopup="true" disabled="disabled"><?= __('Applied') ?></button>
                <?php elseif($applied == 'validate'):?>
                    <?= $this->Form->postLink(__('Validate your application'), ['controller'=>'event_applications','action' => 'validate', $event->id], ['class'=>'btn btn-info']) ?>
                <?php elseif($applied == 'must_login'): ?>
                    <?= $this->Html->link(__('Login to apply'), ['controller'=>'users','action' => 'login']) ?>
                <?php endif; ?>
                </p>
                <div id="div-form-new-needuser">
                </div>
              </div>
            </div>

        </div>

    </div>
<script type="text/template" id='dialog-template'>
  <div class="modal fade" id="modal-form-new-needuser" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title" id="modal-header">
                         <?= __('Application'); ?></h4>
                 </div>
                 <div class="modal-body">
                   <form action="" method="post">
                     <?php
                       echo $this->Form->control('user_id', ['type' => 'hidden', 'value'=>$this->request->Session()->read('Auth.User')['id']]);
                       echo $this->Form->control('event_id', ['type'=> 'hidden', 'value'=>(int)$event->id]);
                     ?>
                     <p id="p-modal-message"><?= __('Please fill the form now'); ?></p>
                     <table cellpadding="5" id="modal-table-needuser" class="table table-striped table-bordered table-hover">
                         <tr>
                             <td valign="top">
                                 <strong style="font-size: 13px;"><?= __('Description'); ?>:</strong><br />
                                 <?= __('Explain yourself briefly'); ?>
                             </td>
                             <td>
                                 <textarea placeholder="<?= __('Explain who you are. Why do you want to apply to this? What makes you different bla bla.'); ?>"
                                     rows="3" id="txtDescription" name="description" class="form-control input-large placeholder"></textarea>
                             </td>
                         </tr>

                     </table>
                   </form>
                     <p id="p-modal-success-message" class="alert alert-success"><?= __('Thanks for application.'); ?></p>
                 </div>
                 <div class="modal-footer">
                     <button type="button" id="btn-modal-vazgec" class="btn btn-default" data-dismiss="modal">
                         <?= _('Cancel'); ?></button>
                     <a  id="btn-modal-kaydet" href="#" class="btn btn-primary"><span class="glyphicon glyphicon-pencil"></span>
                         <?= _('Save'); ?></a> <span id="loaderSaveNeedUserInfo"></span>
                 </div>
             </div>
         </div>
     </div>
</script>
<script type="text/javascript">
  $(function() {
    $('body').append($('#dialog-template').html());
    $(document).on('click', '#btn-modal-kaydet', function() {
      if($('input[name=description]').val() == '') {
        alert('<?= __('Description should be filled.')?>');
        return false;
      }
      $.post( "<?= $this->Url->build([
                  "controller" => "EventApplications",
                  "action" => "add"
              ]);?>", $('#modal-form-new-needuser form').serialize() ).done(function(data) {
                data = JSON.parse(data);
                if(data.success) {
                  alert('<?= __('Application successfully submitted'); ?>');
                  $('#btn-modal-vazgec').click();
                  $('input[nmae=txtDescription]').val('');
                  location.reload();
                } else {
                  alert('<?= __('An error occured'); ?>')
                }
      });

    })
  })
</script>
<style>
  h4 {
    border-bottom: 1px solid #999;
    padding:5px;
  }
</style>
