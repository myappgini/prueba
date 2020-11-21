<?php

$file = 'http://docs.google.com/gview?url=http://www.pdf995.com/samples/pdf.pdf&embedded=true';
$title ='Launch demo modal';
?>
<!-- Button trigger modal -->
<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
  <?php echo $title; ?>
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $title; ?></h4> 
      </div>
      <div class="modal-body">
        <div style="text-align: center;">
<iframe src="<?php echo $file; ?>" 
style="width:500px; height:500px;" frameborder="0"></iframe>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>