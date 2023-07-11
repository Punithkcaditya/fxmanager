<?php if($session->getFlashdata('error')) {?>
                        <div class="alert alert-danger rounded-0 notification-container">
                            <?= $session->getFlashdata('error') ?>
                            <i class="close-notification fas fa-window-close"></i>
                        </div>
                    <?php } else if($session->getFlashdata('success')) { ?>
                        <div class="alert alert-success rounded-0 notification-container">
                            <?= $session->getFlashdata('success') ?>
                            <i class="close-notification fas fa-window-close"></i>
                        </div>
       
					<?php } else if($session->getFlashdata('warning')) { ?>
                        <div class="alert alert-warning rounded-0 notification-container">
                            <?= $session->getFlashdata('warning') ?>
                            <i class="close-notification fas fa-window-close"></i>
                        </div>
                    <?php } else if($session->getFlashdata('display_order')) { ?>
                        <div class="alert alert-warning rounded-0 notification-container">
                            <?= $session->getFlashdata('display_order') ?>
                            <i class="close-notification fas fa-window-close"></i>
                        </div> <?php } else {} ?>

