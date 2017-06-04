// this js is used to help the display of the house list(which is the send layer) and the house detail
// it servers "house_list_dispaly.php", and send request and get data back from "house_list.php" (which is a
// php script used to connect the database and send back data to list all matched houses) and "house_detail.php"
// (which is supporsed to connect all the tables in the database to send back the whole data for house detail)
//hide the detailed house layer
$('#house-detail-page').hide();
//get house from house_list.php
var xmlHttp = new XMLHttpRequest();
xmlHttp.open("POST", "house_list.php", true);
xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
xmlHttp.addEventListener("load", function(event){
    var jsonData = JSON.parse(event.target.responseText);
    
    if (!jsonData.success) {
        alert(jsonData.message);
        return;
    }

    //fetch the array from house_list.php
    var houseArr = jsonData.houseArray;
    // for each house display its info 
    for(var i = 0; i < houseArr.length; ++i){
        // create element for each attribute
        var houseTable = document.getElementById('house-table-body');
        var houseRow = document.createElement('tr');
        var bedrooms  = document.createElement('td');
        var bathrooms = document.createElement('td');
        var value = document.createElement('td');
        var size = document.createElement('td');
        var zestValue = document.createElement('td');
        var address = document.createElement('td');
        var zipcode = document.createElement('td');
        var city = document.createElement('td');
        var state = document.createElement('td');
        
        //make address to be a link
        var addressLink = document.createElement('a');
        addressLink.setAttribute("href", "#!");
        addressLink.setAttribute("id", houseArr[i]['zillow_id']);
        addressLink.setAttribute("name", houseArr[i]['zipcode']);
        addressLink.setAttribute('data-house', houseArr[i]);
        addressLink.textContent  = houseArr[i]['address'];
        address.appendChild(addressLink);
        // set text content for each element
        bedrooms.textContent = houseArr[i]['bedrooms'];
        bathrooms.textContent = houseArr[i]['bathrooms'];
        value.textContent = "\$" + houseArr[i]['value'];
        size.textContent = houseArr[i]['size'];
        zestValue.textContent = "\$" + houseArr[i]['zest_value'];
        zipcode.textContent = houseArr[i]['zipcode'];
        city.textContent  = houseArr[i]['city'];
        state.textContent = houseArr[i]['state'];
        
        // append each element
        houseTable.appendChild(houseRow);
        houseRow.appendChild(address);
        houseRow.appendChild(bedrooms);
        houseRow.appendChild(bathrooms);
        houseRow.appendChild(value);
        houseRow.appendChild(size);
        houseRow.appendChild(zestValue);
        houseRow.appendChild(zipcode);
        houseRow.appendChild(city);
        houseRow.appendChild(state);
        
        // check if value is -1
        bedrooms.textContent = houseArr[i]['bedrooms'];
        bathrooms.textContent = houseArr[i]['bathrooms'];
        value.textContent = "\$" + houseArr[i]['value'];
        size.textContent = houseArr[i]['size'];
        zestValue.textContent = "\$"  + houseArr[i]['zest_value'];
        if (houseArr[i]['bedrooms'] == -1) {
            bedrooms.textContent = "Unknown";
        }
        if (houseArr[i]['bathrooms'] == -1) {
            bathrooms.textContent = "Unknown";
        }
        if (houseArr[i]['value'] == -1) {
            value.textContent = "Unknown";
        }
        if (houseArr[i]['size'] == -1) {
            size.textContent = "Unknown";
        }
        if (houseArr[i]['zest_value'] == -1) {
            zestValue.textContent  = "Unknown";
        }
 
        // add event listener for every link
        addressLink.addEventListener("click", function(event){
            //hide the results div
            $('#house-list-page').hide();
            $('#house-detail-page').show();

            //retrieve data from database
            var dataString = "zillow_id=" + encodeURIComponent(event.target.getAttribute('id')) + "&zipcode=" + encodeURIComponent(event.target.getAttribute('name'));
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("POST","house_detail.php", true);
            xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlHttp.addEventListener("load", function(event){
                var jsonData = JSON.parse(event.target.responseText);
                if (!jsonData.success) {
                    alert(jsonData.message);
                    return;
                }
                var infoArr = jsonData.infoArray;
                // append the info array to the house_display php for the detail page part
                // make the address as the head of house detailed page
                var header = document.createElement('h2');
                header.textContent  = infoArr[0][0]['address'];
                $("#house-detail-page").prepend(header);

                // append other house info as a li
                var value_li = document.createElement('li');
                var size_li = document.createElement('li');
                var bedrooms_li = document.createElement('li');
                var bathrooms_li = document.createElement('li');
                value_li.textContent = "Value: \$" + infoArr[0][0]['value'];
                size_li.textContent = "Size: " + infoArr[0][0]['size'] + " sqrt";
                bedrooms_li.textContent = "Bedrooms: "  + infoArr[0][0]['bedrooms'];
                bathrooms_li.textContent ="Bathrooms: " + infoArr[0][0]['bathrooms'];
                
                if (infoArr[0][0]['bedrooms'] == -1) {
                    bedrooms_li.textContent = "Bedrooms: Unknown";
                }
                if (infoArr[0][0]['bathrooms'] == -1) {
                    bathrooms_li.textContent = "Bathrooms: Unknown";
                }
                if (infoArr[0][0]['value'] == -1) {
                    value_li.textContent = "Value: Unknown";
                }
                if (infoArr[0][0]['size'] == -1) {
                    size_li.textContent = "Size: Unknown";
                }
                
                
                
                
                var value_size = document.getElementById('value-size-list');
                value_size.appendChild(value_li);
                value_size.appendChild(size_li);
                var bed_bath = document.getElementById("bed-bath-list");
                bed_bath.appendChild(bedrooms_li);
                bed_bath.appendChild(bathrooms_li);
                
                
                
                //next: append other info about the house and demorgraphic info
                // remember to add a jquery to realize each toggle

                  //chart handler
                //button toggle
                $('#male_female_button').click(function(){
                    $('#male_female_pop').toggle();
                });
                var malePopulation=infoArr[2][0]['Malepopulation'];
                var femalePopulation=infoArr[2][0]['FemalePopulation'];
                var totalPopulation=malePopulation+femalePopulation;
                //insert gender chart
                $('#male_female_pop').insertFusionCharts({
                    type: 'doughnut2D',
                    width: '400',
                    height: '300',
                    dataFormat: 'json',
                    dataSource:{
                        "chart": {
                            "caption": "Male/Female",
                            "showBorder": "0",
                            "use3DLighting": "0",
                            "enableSmartLabels": "0",
                            "startingAngle": "310",
                            "showLabels": "0",
                            "showPercentValues": "1",
                            "showLegend": "1",
                            "defaultCenterLabel": "Total Population"+totalPopulation,
                            "centerLabel": "Population from $label: $value",
                            "centerLabelBold": "1",
                            "showTooltip": "0",
                            "decimals": "0",
                            "useDataPlotColorForLabels": "1",
                            "theme": "fint"
                        },
                        "data": [
                            {
                                "label": "Female",
                                "value": malePopulation
                            },
                            {
                                "label": "Male",
                                "value": femalePopulation
                            }
                        ]
                    }

                });
                //value income button toggle
                $('#value_income_button').click(function(){
                    $('#value_income').toggle();
                });
                //home value and income chart
                var avgHouseValue=infoArr[2][0]['AverageHouseValue'];
                var incomePerHousehold=infoArr[2][0]['IncomePerHouseHold'];
                $('#value_income').insertFusionCharts({
                    type:'bar3d',
                    width:'400',
                    height:'300',
                    dataFormat:'json',
                    dataSource:{
                    "chart": {
                    "caption": "Economic Information",
                        "yAxisName": "In USD",
                        "numberPrefix": "$",
                        "paletteColors": "#0075c2",
                        "bgColor": "#ffffff",
                        "showBorder": "0",
                        "showCanvasBorder": "0",
                        "usePlotGradientColor": "0",
                        "plotBorderAlpha": "10",
                        "placeValuesInside": "1",
                        "valueFontColor": "#ffffff",
                        "showAxisLines": "1",
                        "axisLineAlpha": "25",
                        "divLineAlpha": "10",
                        "alignCaptionWithCanvas": "0",
                        "showAlternateVGridColor": "0",
                        "captionFontSize": "14",
                        "subcaptionFontSize": "14",
                        "subcaptionFontBold": "0",
                        "toolTipColor": "#ffffff",
                        "toolTipBorderThickness": "0",
                        "toolTipBgColor": "#000000",
                        "toolTipBgAlpha": "80",
                        "toolTipBorderRadius": "2",
                        "toolTipPadding": "5"
                },
                    "data": [
                    {
                        "label": "Average House Value",
                        "value": avgHouseValue
                    },
                    {
                        "label": "Income per Household",
                        "value":incomePerHousehold
                    }
                ]
                }

                });
                //age distribution button toggle
                $('#age_dis_button').click(function () {
                    $('#age_dis').toggle();
                });

                //age distribution chart
                var under10=infoArr[2][0]['AgeUnder10YearsOld'];
                var ten2twn=infoArr[2][0]['AgeFrom10to20'];
                var twn2for=infoArr[2][0]['AgeFrom20to40'];
                var for2six=infoArr[2][0]['AgeFrom40to65'];
                var six2eig=infoArr[2][0]['AgeFrom65to85'];
                var over85=infoArr[2][0]['85YearsOld'];

                //age distribution
                $('#age_dis').insertFusionCharts({
                    type:'doughnut3D',
                    width:'400',
                    height:'300',
                    dataFormat:'json',
                    dataSource:{
                        "chart": {
                            "caption": "Age Distribution",
                            "paletteColors": "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                            "bgColor": "#ffffff",
                            "showBorder": "0",
                            "use3DLighting": "0",
                            "showShadow": "0",
                            "enableSmartLabels": "0",
                            "startingAngle": "310",
                            "showLabels": "0",
                            "showPercentValues": "1",
                            "showLegend": "1",
                            "legendShadow": "0",
                            "legendBorderAlpha": "0",
                            "decimals": "0",
                            "captionFontSize": "14",
                            "toolTipColor": "#ffffff",
                            "toolTipBorderThickness": "0",
                            "toolTipBgColor": "#000000",
                            "toolTipBgAlpha": "80",
                            "toolTipBorderRadius": "2",
                            "toolTipPadding": "5",
                            "useDataPlotColorForLabels": "1"
                        },
                        "data": [
                            {
                                "label":"Under 10",
                                "value":under10
                            },
                            {
                                "label": "10 to 20",
                                "value": ten2twn
                            },
                            {
                                "label": "20 to 40",
                                "value": twn2for
                            },
                            {
                                "label": "40 to 60",
                                "value": for2six
                            },
                            {
                                "label": "60 to 85",
                                "value": six2eig
                            },
                            {
                                "label":"Over 85",
                                "value":over85
                            }
                        ]
                    }
                });

                //race ratio toggle
                $('#race_button').click(function () {
                    $('#race_dis').toggle();
                });
                //value from database
                var white=infoArr[1][0]['population'];
                var black=infoArr[1][1]['population'];
                var hispanic=infoArr[1][2]['population'];
                var asian=infoArr[1][3]['population'];
                var indian=infoArr[1][4]['population'];
                var hawaiian=infoArr[1][5]['population'];
                //race chart
                $('#race_dis').insertFusionCharts({
                    type:'doughnut3D',
                    width:'400',
                    height:'300',
                    dataFormat:'json',
                    dataSource:{
                        "chart": {
                            "caption": "Race Distribution",
                            "paletteColors": "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                            "bgColor": "#ffffff",
                            "showBorder": "0",
                            "use3DLighting": "0",
                            "showShadow": "0",
                            "enableSmartLabels": "0",
                            "startingAngle": "310",
                            "showLabels": "0",
                            "showPercentValues": "1",
                            "showLegend": "1",
                            "legendShadow": "0",
                            "legendBorderAlpha": "0",
                            "decimals": "0",
                            "captionFontSize": "14",
                            "toolTipColor": "#ffffff",
                            "toolTipBorderThickness": "0",
                            "toolTipBgColor": "#000000",
                            "toolTipBgAlpha": "80",
                            "toolTipBorderRadius": "2",
                            "toolTipPadding": "5",
                            "useDataPlotColorForLabels": "1"
                        },
                        "data": [
                            {
                                "label":"White",
                                "value":white
                            },
                            {
                                "label": "Black",
                                "value": black
                            },
                            {
                                "label": "Hispanic",
                                "value": hispanic
                            },
                            {
                                "label": "Asian",
                                "value": asian
                            },
                            {
                                "label": "Indian",
                                "value": indian
                            },
                            {
                                "label":"Hawaiian",
                                "value":hawaiian
                            }
                        ]
                    }

                });

                //calculate function from two position
            function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
                var R = 6371; // Radius of the earth in km
                var dLat = deg2rad(lat2-lat1);  // deg2rad below
                var dLon = deg2rad(lon2-lon1);
                var a =
                        Math.sin(dLat/2) * Math.sin(dLat/2) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                        Math.sin(dLon/2) * Math.sin(dLon/2)
                    ;
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                var d = R * c; // Distance in km
                return d*0.621371;
            }

            function deg2rad(deg) {
                return deg * (Math.PI/180)
            }
                var offender_array=infoArr[5];
                var house_lat=infoArr[0][0]['lat'];
                var house_lng=infoArr[0][0]['lng'];
                var count_offender=0;
                var count_offender5=0;
                var count_offender10=0;
            for(var index=0; index<offender_array.length; index++){
                var dis=getDistanceFromLatLonInKm(house_lat,house_lng,offender_array[index]['lat'],offender_array[index]['lng']);
                if (dis<=1){
                    count_offender++;
                }
                if(dis<=5){
                    count_offender5++;
                }
                if(dis<=10){
                    count_offender10++;
                }
            }
               $('#offender_num1').html(count_offender+' ');
                $('#offender_num5').html(count_offender5+' ');
                $('#offender_num10').html(count_offender10+' ');

                //
                $('#school_list').hide();
                //toggle for schools
                $('#school_button').click(function(){
                    $('#school_list').toggle();
                });

                //toggle for restaurant
                $('#restaurant_list').hide();
                $('#restaurant_button').click(function(){
                    $('#restaurant_list').toggle();
                });
                //list school within one mile
                var school_array=infoArr[3];
                var school_counter=0;
                for(index=0; index<school_array.length; index++){
                    var dis=getDistanceFromLatLonInKm(house_lat,house_lng,school_array[index]['latitude'],school_array[index]['longitude']);

                       if(dis<=1) {
                           $('#school_list').append('<li class="list-group-item">' + school_array[index]['name'] + '</li>');
                           school_counter++;
                       }
                }
                //list Restaurant within 5 mile
                var restaurant_array=infoArr[4];
                var restaurant_counter=0;
                for(index=0; index<restaurant_array.length; index++){
                    var dis=getDistanceFromLatLonInKm(house_lat,house_lng,restaurant_array[index]['latitude'],restaurant_array[index]['longitude']);
                    if(dis<=2){
                        $('#restaurant_list').append('<li class="list-group-item">'+restaurant_array[index]['name'] +'<strong>'+ '                  style: '+'</strong>'+restaurant_array[index]['style'] +'</li>');
                        restaurant_counter++;
                    }
                }











                
                
                
                
                
                
                
                
                
                
                
                
            });
            xmlHttp.send(dataString);
        });
    }
}, false);
xmlHttp.send(null);
