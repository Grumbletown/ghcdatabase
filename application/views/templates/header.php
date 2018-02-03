<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="GHC-Team">
<meta name="description" content="Die offizielle Seite der German Hackerz Community">
<meta name="keywords" content="Hackers Hacking simulator App Game Spiel GHC German Hackerz Community">

<!-- title -->
<title>German Hackers Community</title>

<!-- icon -->
<link rel="shortcut icon" href="<?php echo base_url('assets/images/icon.ico'); ?>">

<!-- google fonts -->
<link href="https://fonts.googleapis.com/css?family=Orbitron:400,500,700,900|Oswald:200,300,400,500,600,700" rel="stylesheet">

<!-- CSS -->

<!-- dark bootstrap theme -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/darkly.min.css') ?>">


    <!-- datatables -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/js/datatables.min.css') ?>">

<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap-datepicker3.min.css') ?>">

<!-- font-awesome -->
<link rel="stylesheet" href="<?php echo base_url('assets/fontawesome/css/font-awesome.min.css');?>">

<!-- select2 -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/select2.min.css');?>">

<!-- OWN FILE: font -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/font.css');?>">

<!-- OWN FILE: site -->
<link rel="stylesheet" href="<?php echo base_url('assets/css/site.css');?>">

<!-- JS -->

<!-- jquery -->
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-2.2.4.min.js') ?>"></script>

<!-- datatables -->
<script type="text/javascript" src="<?php echo base_url('assets/js/dataTables.min.js') ?>"></script>

<!-- bootstrap -->
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<!--- <script type="text/javascript" src="<?php echo base_url('assets/js/dataTables.bootstrap.min.js') ?>"></script> -->

<!-- countdown -->
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.countdown.min.js') ?>"></script>

<!-- clipboard -->
<script type="text/javascript" src="<?php echo base_url('assets/js/clipboard.min.js') ?>"></script>

<!-- select2 -->
<script type="text/javascript" src="<?php echo base_url('assets/js/select2.min.js') ?>"></script>

<!-- OWN FILE: site -->
<script type="text/javascript" src="<?php echo base_url('assets/js/site.js') ?>"></script>




<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<!-- IPRanking-Modal -->
<div class="modal fade" id="IPRankingModal" tabindex="-1" role="dialog" aria-labelledby="IPRankingModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="IPRankingModalLabel">IP Rankings</h3>
      </div>
      <div class="modal-body">

            <div>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs ip-rankings" role="tablist">
                    <li role="presentation" class="active"><a href="#IPsAddedUsers" aria-controls="IPsAddedUsers" role="tab" data-toggle="tab">Most IPs added</a></li>
                    <li role="presentation"><a href="#IPsReportedUsers" aria-controls="IPsReportedUsers" role="tab" data-toggle="tab">Most IPs reported</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" style="margin-top: 25px;">
                    <div role="tabpanel" class="tab-pane fade in active" id="IPsAddedUsers">
                        <h4>Added IPs Ranking</h4>
                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perspiciatis, ex? Quidem voluptatum, exercitationem vero quis odio repellendus rerum voluptates modi beatae placeat repellat quos doloribus molestiae ducimus recusandae earum ut.</p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo unde, ipsa voluptates architecto dolores porro quaerat, velit quod culpa numquam ex voluptas maiores perferendis fugiat molestias laborum non voluptatem consequuntur.</p>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="IPsReportedUsers">
                        <h4>Reported IPs Ranking</h4>
                        <p><strong>We will check reported IPs!<br>If it's a false report this won't count as a reported IP!</strong></p>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo unde, ipsa voluptates architecto dolores porro quaerat, velit quod culpa numquam ex voluptas maiores perferendis fugiat molestias laborum non voluptatem consequuntur.</p>
                   </div>
                </div>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Userprofile-Modal -->
<div class="modal fade" id="userprofileModal" tabindex="-1" role="dialog" aria-labelledby="userprofileModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="userprofileModalLabel">Userprofile</h3>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-xs-12">
                <img src="http://via.placeholder.com/75x75" class="img-responsive img-circle" alt="Responsive image" style="margin: auto;">
            </div>
            <div class="col-xs-12" style="margin-bottom: 20px;">
                <h5 style="text-align: center;" id="userprofileModal-username">Username</h5>
            </div>

            <div class="row" style="margin: auto;">
                <p class="col-xs-5">Reputation:</p>
                <p class="col-xs-7"><strong id="userprofileModal-reputation">999</strong></p>
            </div>

            <div class="row" style="margin: auto;">
                <p class="col-xs-5">Visited:</p>
                <p class="col-xs-7"><strong id="userprofileModal-visited">56</strong> days</p>
            </div>

            <div class="row" style="margin: auto;">
                <p class="col-xs-5">Added IPs:</p>
                <p class="col-xs-7"><strong id="userprofileModal-IPsAdded">25</strong> (<span id="userprofileModal-IPAddedRanking">3</span> Rank)</p>
            </div>

            <div class="row" style="margin: auto;">
                <p class="col-xs-5">Reported IPs:</p>
                <p class="col-xs-7"><strong id="userprofileModal-IPsAdded">66</strong> (<span id="userprofileModal-IPAddedRanking">1</span> Rank)</p>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
