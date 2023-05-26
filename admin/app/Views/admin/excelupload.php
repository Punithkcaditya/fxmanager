<div class="container-fluid pt-8">
    <div class="row">

        <div class="col-lg-12">
        <?= $this->include('message/message') ?>  
            <div class="card shadow">
                <div class="card-header">
                    <h2 class="mb-0">Upload Excel</h2>
                </div>
                <form action="<?php echo base_url('EcxelController/upload');?>" method="post" enctype="multipart/form-data">

                <div class="card-body">
                    <div>
                    <input type="file" id="filename" name="filename" class="dropify" data-max-file-size="2M" />
          <div>
                    <input type="submit" name="submit" value="Upload" class="btn btn-primary mt-3 mb-1" />
                </div>
                </form>
            </div>
        </div>
    </div>

</div>