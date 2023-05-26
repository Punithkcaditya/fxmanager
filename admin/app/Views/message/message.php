<?php if($session->getFlashdata('error')) {?>
                        <div class="alert alert-danger rounded-0">
                            <?= $session->getFlashdata('error') ?>
                        </div>
                    <?php } else if($session->getFlashdata('success')) { ?>
                        <div class="alert alert-success rounded-0">
                            <?= $session->getFlashdata('success') ?>
                        </div>
       
					<?php } else if($session->getFlashdata('warning')) { ?>
                        <div class="alert alert-warning rounded-0">
                            <?= $session->getFlashdata('warning') ?>
                        </div>
                    <?php } else if($session->getFlashdata('display_order')) { ?>
                        <div class="alert alert-warning rounded-0">
                            <?= $session->getFlashdata('display_order') ?>
                        </div> <?php } else {} ?>

