<div class="page-title page-title-small nobackdrnd">

    <div class="location_show">
        <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-arrow-left"
                style="color:#fff"></i></a>
        <h2 class="header-icon header-icon-1"><a href="#" data-menu="ad-timed-3" data-timed-ad="0"
                <?=!empty(get_cookie("location")) ? '' : 'data-auto-show-ad="1"'?>
                class="navigate btn  btn-full rounded-s shadow-xl  font-900 bg-highlight me-3 ms-3 mb-4"><?=!empty(get_cookie("location")) ? get_cookie("location") : "Location"?><i
                    class='fas fa-angle-down' style='font-size:13px'></i></a>
        </h2>

    </div>
    <!-- <a href="#" data-menu="menu-main" class="bg-fade-highlight-light shadow-xl preload-img" data-src="<?=base_url("images/avatars/5s.png")?>"></a> -->
    <!-- <a href="#" data-menu="menu-main" class="bg-fade-highlight-light  preload-img" ><i class="fa-solid fa-bars" style="font-size: 26px;"></i></a> -->
    <img src="<?php echo base_url("assets/img/car-icon-white.png") ?>" class="caricon" />
    <button type="button" onclick="toggler();" id="header-3" data-bs-toggle="dropdown"
        class="header-icon  header-icon-3 font-20" style="position: absolute; top: 0px;right: 0px;color: #fff;"> <i
            class="fa fa-chevron-down font-10 accordion-icon font-5 pe-3" id="uptop"></i></button>
    <p class="mb-n1 color-highlightnewstyle"><?php
            echo '<script type="text/javascript">
            if(getCookie("modelnameforbook")){
                document.write(getCookie("modelnameforbook"));        
            }
            </script>';?></p>
    <div class="dropdown-menu custheight bg-theme border-0 shadow-l rounded-s me-2 mt-2" aria-labelledby="header-3">
        <p class="text-center" id="temp">Select Vehicle Type</p>
        <div class="divider mb-0"></div>
        <div class="list-group list-custom-small ps-2 pe-3">
            <span id="myId"><span>
                    <script type="text/javascript">
                    var baseURL = "<?php echo base_url(); ?>";
                    cookie_count_new = getCookie('cookie_count');
                    yearname_count_new = getCookie("yearname" + cookie_count_new);
                    if (cookie_count_new) {
                        myId = document.getElementById('myId');
                        document.getElementById("temp").style.display = "none";
                        myId.innerHTML +=
                            '<div class="card card-style no-boxshadow"  style="padding: 12px 0px;"><div class="content mb-0"><h3 class="font-600" style="margin-bottom: 29px;">My Vehicles</h3></div><div id="itemsContainer"></div><div class="align-self-center ms-auto text-center" style="display: contents;"><a href="http://localhost/autobilbayt/"><span class="icon icon-m bg-fade-gray-light color-theme rounded-xl"><i class="fa fa-plus opacity-70"></i></span><strong class="d-block pt-1 color-theme opacity-70 font-13" style="margin-top: -28px;">Add New</strong></a></div></div>'
                        demop = document.getElementById('itemsContainer');
                        listCookies();

                        function listCookies() {
                            for (let i = 1; i <= cookie_count_new; i++) {
                                brandname = getCookie("brandname" + i);
                                modelname = getCookie("modelname" + i);
                                yearname = getCookie("yearname" + i);
                                carname = getCookie("carname" + i);
                                if (brandname != null && (modelname != null && yearname != null)) {
                                    demop.innerHTML +=
                                        '<div class="selected-vehicle"><span class="badge2" style="padding-left: 20px;">' +
                                        carname +
                                        '</span><div class="ms-3 me-3 alert alert-small rounded-s shadow-xl " role="alert"><a href="#" class="bookingdetails collapsed" data-brandnameforbook="' +
                                        brandname + '" data-modelnameforbook="' + modelname +
                                        '"  data-yearnameforbook="' + yearname + '" id="showactive' + i +
                                        '" onclick="traverse(this);"><h4 style="padding: 14px !important;">' +
                                        brandname + "," + modelname + "," + yearname +
                                        '</h4></a><button type="button" data-bs-dismiss="alert" data-buttonid=' + i +
                                        ' id="close" class="close color-black opacity-60 font-16" aria-label="Close">×</button></div></div>';
                                }
                            }


                        }
                    }

                    var noteid = localStorage.getItem("noteid")
                    if (noteid) {
                        newEl = document.createElement('i');
                        newEl.setAttribute("class", "fa fa-check font-16 color-green-dark custom");
                        document.getElementById(noteid).appendChild(newEl);
                    }


                    function traverse(elem) {
                        localStorage.setItem("noteid", elem.id);
                    }

                    function toggler() {
                        uptop = document.getElementById('uptop');
                        if (uptop.classList.contains('fa-chevron-down')) {
                            uptop.classList.remove("fa-chevron-down");
                            uptop.classList.add("fa-chevron-up");
                        } else {
                            uptop.classList.remove("fa-chevron-up");
                            uptop.classList.add("fa-chevron-down");
                        }

                    }
                    </script>
        </div>
    </div>

</div>

<div class="card header-card shape-rounded" data-card-height="150">
    <div class="card-overlay bg-highlight opacity-95"></div>
    <div class="card-overlay dark-mode-tint"></div>
    <div class="card-bg preload-img" data-src="#"></div>
</div>