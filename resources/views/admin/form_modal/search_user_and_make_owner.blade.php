<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header filter-form">
                    <div class="col-md-12 input-group pull-left ">
                        <input type="text" id="search_text2" class="form-control" name="search_text"
                            placeholder="Search by keyword." onkeyup="search2(1,'FILTER')" value="">
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-flat" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div><!-- /.box-header -->

            </div><!-- /.box -->

            <!-- user-card-row -->
            <div class="row">

                <!-- <div class="col-xs-12">
                    <div id="no-data-box_user" style="display:none;">Record not found...!</div>
                </div> -->
                <div class="user-card-row" id="no-data-box_user" style="display:none;">
                    <div class="text-center">
                        <div class="col-xs-12" >
                            <h4>Record not found...!</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 append-users" id="search_result_box_user">
                </div>

                <div class="text-center" id="load_more_btn_user" style="display:none">
                    <div>
                        <a id="loadmore_user" class="btn btn-primary" onclick="search2(1)">
                            Load More</a>
                    </div>
                </div>
                <div class="text-center">
                    <div class="sk-circle" id="loadmoreIcon_user" style="display:none">
                        <div class="sk-circle1 sk-child"></div>
                        <div class="sk-circle2 sk-child"></div>
                        <div class="sk-circle3 sk-child"></div>
                        <div class="sk-circle4 sk-child"></div>
                        <div class="sk-circle5 sk-child"></div>
                        <div class="sk-circle6 sk-child"></div>
                        <div class="sk-circle7 sk-child"></div>
                        <div class="sk-circle8 sk-child"></div>
                        <div class="sk-circle9 sk-child"></div>
                        <div class="sk-circle10 sk-child"></div>
                        <div class="sk-circle11 sk-child"></div>
                        <div class="sk-circle12 sk-child"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>