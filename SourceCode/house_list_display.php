<?php
session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>House List</title>
    </head>
    <body class="container">
    <!--navigator-->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">St.Lious Housing</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.html">Back to Home</a></li>
            </ul>
        </div>
    </nav>

    <!--house list page (second layer)-->
        <div id="house-list-page">
            <h1 class="page-header">Avaliable Houses</h1> <br>
            <table id="house-table" class="table">
                <thead>
                    <tr>
                        <th>Address</td>
                        <th>Bedrooms</td>
                        <th>Bathrooms</td>
                        <th>Value</td>
                        <th>Size</td>
                        <th>Zest_value</td>
                        <th>Zipcode</td>
                        <th>City</td>
                        <th>State</td>
                    </tr>
                </thead>
                <tbody id="house-table-body">
                    
                </tbody>
            </table>
        </div>

    <!--house detailed page layer -->
        <div id="house-detail-page" class="row">
            <!-- house detail page, when this div is displayed, the above house-list-page should hide-->
            <div id="house-info-div" class="row" style="color: gray; font-size: 1.8em; padding: 15px; padding-left: 40px">
                <ul id="value-size-list" style="display: inline-block; width: 30%;">
                </ul>
                <ul id="bed-bath-list" style="display: inline-block; width: 30%">
                </ul>
            </div>
            <br>
            <br>
            <!--offender information-->
            <div class="alert alert-danger">
                <strong>Danger!</strong> <span id="offender_num1"></span>Recorded Offenders within 1 Mile of the house!
            </div>
            <div class="alert alert-warning">
                <strong>Warning!</strong> <span id="offender_num5"></span>Recorded Offenders within 5 Miles of the house!
            </div>
            <div class="alert alert-info">
                <strong>Info!</strong><span id="offender_num10"></span> Recorded Offenders within 10 Miles of the house!
            </div>
            <br>

            <!--school -->
            <div class="col-md-offset-0">
            <button class="btn btn-success" id="school_button">Schools within 1 mile click to show them</button>
            </div>

            <div class="row">
            <ul class="list-group" id="school_list">

            </ul>
            </div>
            <br>

            <!--restaurant-->
            <div class="col-md-offset-0">
                <button class="btn btn-success" id="restaurant_button">Restaurants within 2 miles click to show them</button>
            </div>

            <div class="row">
                <ul class="list-group" id="restaurant_list">

                </ul>
            </div>
            <br>

            <!--detailed information display-->
            <div id="information_layer">
                <!--button id="demography_button" class="btn btn-info">Show Demography Information</button-->
                <div id="demography" class="list-group">
                    <div  id="demography_collection">
                        <!--first row male/female economic information -->
                        <div class="row">
                            <div class="col-md-6">
                            <button class="btn btn-primary btn-lg btn-block" id="male_female_button">Female/Male</button>
                            </div>
                            <div class="col-md-6">
                            <button class="btn btn-primary btn-lg btn-block" id="value_income_button">Economic Information</button>
                            </div>
                        </div>
                        <!--male/female house value -->
                        <div class="row">
                        <!--male female chart -->
                        <div  class="col-md-6" id="male_female_pop"></div>
                        <!--House value and income-->
                        <div class="col-md-6" id="value_income"></div>
                        </div>


                        <!--second row age distribution -->
                        <div class="row">
                          <div class="col-md-6">
                              <button class="btn btn-primary btn-lg btn-block" id="age_dis_button">Age Distribution</button>
                          </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-lg btn-block" id="race_button">Race</button>
                           </div>
                         </div>

                        <div class="row">
                            <!--age distribution-->
                            <div class="col-md-6" id="age_dis"></div>
                            <!--race distribution-->
                            <div class="col-md-6" id="race_dis"></div>
                       </div>
                </div>

            </div>
        </div>


        
        
        
        
        
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!--Jquery-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <!--arcgis-->
        <link rel="stylesheet" href="https://js.arcgis.com/3.16/esri/css/esri.css">
        <script src="https://js.arcgis.com/3.16/"></script>
        <!--Fusion Chart toolkit -->
        <script type="text/javascript" src="fusioncharts-suite-xt/js/fusioncharts.js"></script>
        <script type="text/javascript" src="fusioncharts-suite-xt/js/themes/fusioncharts.theme.fint.js"></script>
        <script type="text/javascript" src="fusioncharts-jquery-plugin/src/fusioncharts-jquery-plugin.js"></script>

        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="house_list_function.js"></script>
    </body>

</html>