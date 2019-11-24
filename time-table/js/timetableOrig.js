/*
    @ 2016 Lukáš Veteška
    Time Table plugin
*/

(function($, window, document, undefined)
{
    "use strict";
    
    // utility
    function shortenText(text, len)
    {
        var chars = 0;
        
        var first = 2; // 5, 8, 13
        var second = 3; // 5, 8, 13
        
        var startIndex = 2;
        
        if(tt.options.accuracy == 0.5)
            startIndex = 1;
        else if(tt.options.accuracy == 1)
            startIndex = 0;
        
        for(var i = 0; i < len - startIndex; i++)
        {
            if(i % 2 != 0)
            {
                if(i >= startIndex)
                    first = second + first;
                
                chars += first;
            }
            else
            {
                if(i >= startIndex)
                    second = second + first;

                chars += second;
            }
        }
        
        chars *= 4 / (1 / tt.options.accuracy);
        
        if((tt.options.accuracy == 0.5 && len == 2 && chars > 5) || (tt.options.accuracy == 1 && len == 1 && chars > 5))
            chars = 5;
        if(tt.options.accuracy == 0.25 && len == 5 && chars > 7)
            chars = 7;
        
        if(text.length > chars)
            return text.substr(0, chars) + "...";
        
        return text;
    }
    
    function calcWidth(target)
    {
        // const is 30
        var width = target.width();
        
        target.children(".text").css("width", width + "px");//(width - 30) + "px");
    }
    
    function exists(value)
    {
        if(value === undefined)
            return "";
        
        return value;
    }
    
    var datePicker = null;
    var map = null;
    var markers = [];
    var markersI = [];
    var ttResources = [];
    var ttEvents = [];
    var date = null;
    var tt = null;
    var isResizing = false;
    var lastDownX = 0;
    var colWidth = 0;
    var colStartWidth = 0;
    var colConst = null;
    var colStartW = 0;
    var infoWindow = null;
    
    var ttTimeTable = null;
    var ttTimeTableMove = 0;
    
    function timeTable(container, options)
    {
        options = this.options = $.extend({}, timeTable.defaults, options);
        
        date = moment();
        
        /// skelet
        // top part: tasks (calendar > taskList), mapWidget
        container.append('<div class="col-lg-12" id="ttTop"></div>');
        $("#ttTop").append('<div id="ttDetail"></div>');
        $("#ttTop").append('<div class="col-lg-4" id="ttTasks"></div>');
        $("#ttTop").append('<div class="col-lg-8" id="mapWidget"></div>');
        $("#ttDetail").append('<h4>Informace o události</h4>');
        $("#ttDetail").append('<form class="form-horizontal"> <div class="form-group"> <label class="col-sm-4 control-label">Název</label> <div class="col-sm-8"> <p class="form-control-static" id="ttDetailName">email@example.com</p></div></div> <div class="form-group"> <label class="col-sm-4 control-label">Adresa</label> <div class="col-sm-8"> <p class="form-control-static" id="ttDetailAddress">email@example.com</p></div></div> <div class="form-group"> <label class="col-sm-4 control-label">Zákazník</label> <div class="col-sm-8"> <p class="form-control-static" id="ttDetailCustomer">email@example.com</p></div></div> </form>');
        $("#ttDetail").append('<button class="btn btn-block btn-danger" id="ttDetailClose">Zavřít</button>');
        $("#ttTasks").append('<div class="form-group"> <div class="input-group date" data-provide="datepicker" id="datePicker"> <span class="input-group-addon" id="ttPrevDate"><span class="glyphicon glyphicon-chevron-left"></span></span> <input type="text" class="form-control"> <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> <span class="input-group-addon" id="ttNextDate"><span class="glyphicon glyphicon-chevron-right"></span></span></div></div>');
        $("#ttTasks").append('<div class="panel panel-primary"> <div class="panel-heading">Nenaplánované zakázky</div> <div class="panel-body"> <div class="list-group" id="taskList"></div></div></div>');
        // bot part: time table
        container.append('<div class="col-lg-12" id="ttBot"></div>');
        $("#ttBot").append('<table class="table table-bordered" id="ttTable"></table>');
    }
    
    timeTable.prototype = 
    {
        constructor: timeTable,
        _init: function()
        {
            tt = this;
            
            var $this = this;
            var options = this.options;
            
            if(options.resources != null)
                ttResources = options.resources;
            
            if(options.events != null)
                ttEvents = options.events;
            
            var accuracy = 1 / options.accuracy;
            
            var ths = "<th>&nbsp;</th>";
            
            for(var h = options.firstHour; h <= options.lastHour; h++)
                ths += "<th colspan='" + accuracy + "'>" + h + ":00</th>";
            
            $("#ttTable").append("<tr>" + ths + "</tr>");
            
            $("#ttTop").css("min-height", options.minMapHeight);
            
            google.maps.event.addDomListener(window, 'load', this._initGoogleMaps);
            
            this._load(date);
            this.showMarkers();
            
            // datePicker
            datePicker = $('#datePicker');
            datePicker.datetimepicker(
            {
                format: "DD/MM/YYYY"
            });
            datePicker.on("dp.change", function(e)
            {
                if(e.oldDate == e.date || e.oldDate == null)
                    return;
                
                if(options.onDateChange != null)
                    options.onDateChange(e.oldDate, e.date);
                
                date = moment(e.date);
                
                $("#datePicker input").val(date.format("DD/MM/YYYY"));
                
                $this._load(date);
                $this.showMarkers();
                $this.zoomOut();
            });
            datePicker = $('#datePicker').data("DateTimePicker");
            
            $("#datePicker input").val(date.format("DD/MM/YYYY"));
            
            $("body").on("click", "#ttPrevDate", function(e)
            {
                e.preventDefault();
                
                var currDate = $('#datePicker').data("DateTimePicker").date();
                currDate.subtract(1, "d");
                
                $('#datePicker').data("DateTimePicker").date(currDate);
                $('#datePicker').data("DateTimePicker").hide();
            });
            
            $("body").on("click", "#ttNextDate", function(e)
            {
                e.preventDefault();
                
                var currDate = $('#datePicker').data("DateTimePicker").date();
                currDate.add(1, "d");
                
                $('#datePicker').data("DateTimePicker").date(currDate);
                $('#datePicker').data("DateTimePicker").hide();
            });
            
            // center map on click
            $("body").on("click", "#ttTable .subTask, #taskList .list-group-item", function(e)
            {
                e.preventDefault();
                
                var task = $(this);
                
                var bounds = new google.maps.LatLngBounds();
            
                for(var j = 0; j < markersI.length; j++)
                {
                    if(markersI[j].taskID == task.data("id"))
                    {
                        bounds.extend(markersI[j].getPosition());
                        
                        if(options.zoomOnClick)
                            map.fitBounds(bounds);
                        else
                            map.setCenter(markersI[j].getPosition());

                        break;
                    }
                }
            });
            
            // show details event
            $("body").on("click", ".subTask a", function(e)
            {
                e.preventDefault();
                
                if(infoWindow)
                    infoWindow.close();
                
                var parent   = $(this).parent();
                var customer = $("#ttTable tr td[data-id='" + parent.data("resource") + "']:first-child").text();
                
                for(var t = 0; t < ttEvents.length; t++)
                {
                    if(ttEvents[t].id != parent.data("id"))
                        continue;
                    
                    var marker = null;
                    
                    for(var j = 0; j < markersI.length; j++)
                    {
                        if(markersI[j].taskID == ttEvents[t].id)
                        {
                            marker = markersI[j];
                            
                            break;
                        }
                    }
                    
                    $this.showDetails(marker, ttEvents[t], customer);

                    break;
                }
            });
            
            // close details event
            $("body").on("click", "#ttDetailClose", function(e)
            {
                e.preventDefault();
                
                $this.closeDetails();
            });
            
            // expand task
            $("body").on("mousedown", ".ttDrag", function(e)
            {
                var secondTh = $("#ttTable tr th:eq(2)");
                
                isResizing    = true;
                lastDownX     = e.clientX;
                colWidth      = secondTh.width() / parseInt(secondTh.attr("colspan"));
                colStartWidth = $(this).parent().width();
                colConst      = $(this).parent();
                colStartW     = parseInt(colConst.parent().attr("colspan"));
                
                colConst.attr("draggable", false);
            });
            
            $(document).on("mousemove", function(e)
            {
                if(!isResizing)
                    return;
                
                var diff = lastDownX - e.clientX;
                
                if(Math.abs(diff) >= colWidth - 1)
                {
                    var td = colConst.parent();
                    var colspan = parseInt(td.attr("colspan"));
                    
                    if(diff < 0 && td.next().html() == "&nbsp;")
                    {
                        td.attr("colspan", colspan + 1);
                        td.next().remove();
                    }
                    else if(diff > 0 && colspan > 1)
                    {
                        td.attr("colspan", colspan - 1);
                        td.after("<td>&nbsp;</td>");
                    }
                    
                    //colConst.children(".text").text(shortenText(colConst.data("name"), diff > 0 ? colspan - 1 : colspan + 1));
                    //colspan.children(".text")
                    calcWidth(colConst);
                    
                    lastDownX = e.clientX;
                }
            });
            
            $(document).on("mouseup", function(e)
            {
                if(isResizing)
                {
                    isResizing = false;
                
                    if(colConst != null)
                    {
                        var td = colConst.parent();
                        var diff = parseInt(td.attr("colspan")) - colStartW;
                        var id = colConst.data("id");
                        var step = 60 / (1 / options.accuracy);
                        var time = moment(colConst.data("end"));
                        
                        if(diff < 0)
                            time = time.subtract(step * diff, "minutes").format(options.dateFormat);
                        else
                            time = time.add(step * diff, "minutes").format(options.dateFormat);
                            
                        colConst.attr("draggable", true);
                        colConst.attr("data-end", time);
                        
                        for(var t = 0; t < ttEvents.length; t++)
                        {
                            if(ttEvents[t].id != id)
                                continue;
                            
                            ttEvents[t].end = time;
                            
                            if(options.onUpdate != null)
                                options.onUpdate(ttEvents[t]);
                            
                            break;
                        }
                        
                        colConst = null;
                    }
                }
            });
            
            // drag & drop
            $("body").on("dragstart", "#taskList .list-group-item, #ttTable .subTask", function(e) // drag started
            {
                var clone = this.cloneNode(true);
                clone.style.position = "absolute";
                clone.style.left = "-99999px";
                clone.style.top  = "-99999px";
                clone.id = "cloneTask";
                
                document.body.appendChild(clone);
                
                //e.originalEvent.dataTransfer.setData("timeTableMove", $(this).hasClass("list-group-item") ? 0 : 1);
                //e.originalEvent.dataTransfer.setData("timeTable", $(this).data("id"));
                ttTimeTableMove = $(this).hasClass("list-group-item") ? 0 : 1;
                ttTimeTable = $(this).data("id");
                e.originalEvent.dataTransfer.setDragImage(clone, 0, 0);
            });
            
            $("body").on("dragend", "#taskList .list-group-item, #ttTable .subTask", function(e)
            {
                if($("#cloneTask").length > 0)
                    $("#cloneTask").remove();
            });

            $("body").on("drop", "#ttTable tr td:gt(0)", function(e) // dropped
            {
                e.preventDefault();
                
                if($(this).html() != "&nbsp;")
                    return;

                var data = ttTimeTable;//e.originalEvent.dataTransfer.getData("timeTable");
                var move = ttTimeTableMove;//e.originalEvent.dataTransfer.getData("timeTableMove");
                
                var target    = move == 1 ? $(".subTask[data-id='" + data + "']") : $("#taskList .list-group-item[data-id='" + data + "']");
                var currIndex = $(this).index();
                
                if(move == 1)
                {
                    var td  = target.parent("td");
                    var td2 = $(this);
                    var tr  = $(this).parent();
                    
                    var tdLen = parseInt(td.attr("colspan"));
                    
                    // check enough space
                    var counter  = 0;

                    tr.children("td:not(:first-child)").each(function(index, element)
                    {
                        if(index < currIndex - 1)
                            return;
                        
                        if(currIndex + tdLen - 1 < index || ($(element).html() != "&nbsp;" && $(element).html() != td.html()))
                            return false;
                        
                        if($(element).html() != "&nbsp;" && $(element).html() == td.html())
                            counter += parseInt(td.attr("colspan"));
                        else
                            counter++;
                    });
                    
                    if(counter < tdLen) // not available enough space
                        return;
                    
                    td.attr("colspan", 1);
                    td2.attr("colspan", tdLen);
                    
                    for(var i = 0; i < tdLen - 1; i++)
                        td.after("<td>&nbsp;</td>");
                    
                    var resource = tr.children("td:eq(0)").data("id");
                    
                    var startDate = moment().set({"year": date.year(), "month": date.month(), "date": date.date(), "hour": options.firstHour, "minute": 0, "second": 0}).add((currIndex - 1) * 60 * options.accuracy, "m").format(options.dateFormat);
                    var endDate   = moment(startDate).add(tdLen * 60 * options.accuracy, "m").format(options.dateFormat);
                    
                    target.attr("data-start", startDate);
                    target.attr("data-end", endDate);
                    target.attr("data-resource", resource);
                    
                    td2.html("").append(target); // eq e.target.appenChild
                    td.html("&nbsp;");
                    
                    for(var i = 0; i < tdLen - 1; i++)
                        td2.next().remove();
                    
                    for(var t = 0; t < ttEvents.length; t++)
                    {
                        if(ttEvents[t].id != target.data("id"))
                            continue;
                        
                        ttEvents[t].resource = resource;
                        ttEvents[t].start    = startDate;
                        ttEvents[t].end      = endDate;

                        if(options.onUpdate != null)
                            options.onUpdate(ttEvents[t]);
                        
                        break;
                    }
                }
                else
                {
                    var tr       = $(this).parent();
                    var timeUnit = $this.getTimeUnit();
                    var counter  = 0;

                    tr.children("td:not(:first-child)").each(function(index, element)
                    {
                        if(index < currIndex - 1)
                            return;
                        
                        if(currIndex + timeUnit - 1 < index || $(element).html() != "&nbsp;")
                            return false;
                        
                        counter++;
                    });
                    
                    if(counter < timeUnit) // not available enough space
                        return;
                    
                    var resource = tr.children("td:eq(0)").data("id");
                    
                    var startDate = moment().set({"year": date.year(), "month": date.month(), "date": date.date(), "hour": options.firstHour, "minute": 0, "second": 0}).add((currIndex - 1) * 60 * options.accuracy, "m").format(options.dateFormat);
                    var endDate   = moment(startDate).add(timeUnit * 60 * options.accuracy, "m").format(options.dateFormat);

                    var td = $(this);
                    td.html('<div class="subTask" draggable="true" data-id="' + target.data("id") + '" data-name="' + target.data("name") + '" data-address="' + target.data("address") + '" data-lat="' + target.data("lat") + '" data-lng="' + target.data("lng") + '" data-customer="' + target.data("customer") + '" data-resource="' + resource + '" data-start="' + startDate + '" data-end="' + endDate + '" data-toggle="tooltip" data-placement="top" title="' + target.data("name") + '"><span class="text">' + /*shortenText(target.data("name"), timeUnit)*/ target.data("name") + '</span> <a href="#"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> <div class="ttDrag" draggable="false"></div></div>');
                    td.attr("colspan", timeUnit);

                    for(var i = 0; i < timeUnit - 1; i++)
                        td.next().remove();
                    
                    calcWidth(td.children(".subTask")); // calc right here!
                    
                    for(var t = 0; t < ttEvents.length; t++)
                    {
                        if(ttEvents[t].id != target.data("id"))
                            continue;
                        
                        ttEvents[t].resource = resource;
                        ttEvents[t].start    = startDate;
                        ttEvents[t].end      = endDate;

                        if(options.onUpdate != null)
                            options.onUpdate(ttEvents[t]);
                        
                        break;
                    }
                    
                    target.remove();
                }
            });

            $("body").on("dragover", "#ttTable tr td:gt(0), #ttTop .panel", function(e) // allow drop
            {
                if($(this).is("td") && $(this).html() != "&nbsp;")
                {
                    e.originalEvent.dataTransfer.dropEffect = "none";
                    
                    return false;
                }
                else if($(this).is("td"))
                {                
                    var data = ttTimeTable;//e.originalEvent.dataTransfer.getData("timeTable");
                    var move = ttTimeTableMove;//e.originalEvent.dataTransfer.getData("timeTableMove");

                    var target    = move == 1 ? $(".subTask[data-id='" + data + "']") : $("#taskList .list-group-item[data-id='" + data + "']");
                    var currIndex = $(this).index();

                    if(move == 1)
                    {
                        var td  = target.parent("td");
                        var td2 = $(this);
                        var tr  = $(this).parent();

                        var tdLen = parseInt(td.attr("colspan"));

                        // check enough space
                        var counter  = 0;

                        tr.children("td:not(:first-child)").each(function(index, element)
                        {
                            if(index < currIndex - 1)
                                return;

                            if(currIndex + tdLen - 1 < index || ($(element).html() != "&nbsp;" && $(element).html() != td.html()))
                                return false;

                            if($(element).html() != "&nbsp;" && $(element).html() == td.html())
                                counter += parseInt(td.attr("colspan"));
                            else
                                counter++;
                        });

                        if(counter < tdLen) // not available enough space
                        {
                            e.originalEvent.dataTransfer.dropEffect = "none";
                    
                            return false;
                        }
                    }
                    else
                    {
                        var tr       = $(this).parent();
                        var timeUnit = $this.getTimeUnit();
                        var counter  = 0;

                        tr.children("td:not(:first-child)").each(function(index, element)
                        {
                            if(index < currIndex - 1)
                                return;

                            if(currIndex + timeUnit - 1 < index || $(element).html() != "&nbsp;")
                                return false;

                            counter++;
                        });

                        if(counter < timeUnit) // not available enough space
                        {
                            e.originalEvent.dataTransfer.dropEffect = "none";
                    
                            return false;
                        }
                    }
                }
                
                // finish => allow drop
                e.originalEvent.dataTransfer.dropEffect = "move";
                e.preventDefault();
            });
            
            // drag & drop back!
            $("body").on("drop", "#ttTop .panel", function(e)
            {
                e.preventDefault();
                
                var data = ttTimeTable;//e.originalEvent.dataTransfer.getData("timeTable");
                var move = ttTimeTableMove;//e.originalEvent.dataTransfer.getData("timeTableMove");
                
                if(move != 1) // moving from #taskList to #taskList
                    return;
                
                var target = $(".subTask[data-id='" + data + "']");
                
                var td = target.parent("td");
                
                for(var t = 0; t < ttEvents.length; t++)
                {
                    if(ttEvents[t].id != data)
                        continue;

                    ttEvents[t].resource = null;
                    
                    $("#taskList").append('<a href="#" class="list-group-item" draggable="true" data-id="' + ttEvents[t].id + '" data-name="' + ttEvents[t].name + '" data-address="' + ttEvents[t].address + '" data-lat="' + ttEvents[t].lat + '" data-lng="' + ttEvents[t].lng + '" data-customer="' + ttEvents[t].customer + '" data-start="' + ttEvents[t].start + '" data-end="' + ttEvents[t].end + '"> <h4 class="list-group-item-heading">' + ttEvents[t].name + '</h4> <p class="list-group-item-text">' + ttEvents[t].address + '</p></a>');

                    if(options.onUpdate != null)
                        options.onUpdate(ttEvents[t]);

                    break;
                }
                
                //target.remove();
                
                var len = parseInt(td.attr("colspan"));
                
                for(var i = 1; i < len; i++)
                    td.after("<td>&nbsp;</td>");
                
                td.attr("colspan", 1);
                td.html("&nbsp;");
            });
        },
        _initGoogleMaps: function()
        {
            map = new google.maps.Map(document.getElementById("mapWidget"),
            {
                zoom: 7,
                center:
                {
                    lat: 49.81,
                    lng: 15.5
                }
            });
        },
        _load: function(activeDate)
        {
            var options = this.options;
            
            // fill data if there is any
            var accuracyGen = "";
            
            for(var i = 0; i < this.getColumns(); i++)
                accuracyGen += "<td>&nbsp;</td>";
            
            // clear first
            markers = [];
            
            $("#ttTable").find("tr:gt(0)").remove();
            $("#taskList").html("");
            
            // resources - customers
            for(var r = 0; r < ttResources.length; r++)
            {
                var tds = "<td data-id='" + ttResources[r].id + "' data-lat='" + exists(ttResources[r].lat) + "' data-lng='" + exists(ttResources[r].lng) + "'>" + ttResources[r].name + "</td>";
                tds += accuracyGen;

                $("#ttTable").append("<tr>" + tds + "</tr>");

                if(exists(ttResources[r].lat) && exists(ttResources[r].lng))
                    markers.push($.makeArray({ "lat": ttResources[r].lat, "lng": ttResources[r].lng, "title": ttResources[r].name, "task": false }));
            }
            
            // events - tasks
            // handle without resources
            for(var t = 0; t < ttEvents.length; t++)
            {
                if(ttEvents[t].resource != null)
                    continue;

                $("#taskList").append('<a href="#" class="list-group-item" draggable="true" data-id="' + ttEvents[t].id + '" data-name="' + ttEvents[t].name + '" data-address="' + ttEvents[t].address + '" data-lat="' + ttEvents[t].lat + '" data-lng="' + ttEvents[t].lng + '" data-customer="' + ttEvents[t].customer + '" data-start="' + ttEvents[t].start + '" data-end="' + ttEvents[t].end + '"> <h4 class="list-group-item-heading">' + ttEvents[t].name + '</h4> <p class="list-group-item-text">' + ttEvents[t].address + '</p></a>');
                
                markers.push($.makeArray({ "lat": ttEvents[t].lat, "lng": ttEvents[t].lng, "title": ttEvents[t].name, "task": true, "taskID": ttEvents[t].id }));
            }

            // with resources
            var startDate = moment().set({"year": activeDate.year(), "month": activeDate.month(), "date": activeDate.date(), "hour": options.firstHour, "minute": 0, "second": 0});
            
            for(var t = 0; t < ttEvents.length; t++)
            {
                if(ttEvents[t].resource == null)
                    continue;

                var datetime = moment(ttEvents[t].start);

                if(datetime.format("MM/DD/YYYY") != activeDate.format("MM/DD/YYYY"))
                    continue;
                
                var datetimeEnd = moment(ttEvents[t].end);
                
                var duration = moment(datetimeEnd.diff(datetime)).minutes() + 60 * (moment(datetimeEnd.diff(datetime)).hours() - 1);
                var len = Math.round(duration / (60 / (1 / options.accuracy)));
                
                var cols = this.getColumns();
                var col  = Math.round((moment(datetime.diff(startDate)).minutes() + 60 * (moment(datetime.diff(startDate)).hours() - 1)) / (60 / (1 / options.accuracy)));
                
                // calc index
                var tr = $("#ttTable tr td[data-id='" + ttEvents[t].resource + "']:first-child").closest("tr");
                
                var ind = 0;
                var counter = 0;
                
                tr.children("td:not(:first-child)").each(function(index, element)
                {
                    counter += $(element).attr("colspan") === undefined ? 1 : parseInt($(element).attr("colspan"));
                    
                    ind = index;
                    
                    if(counter > col)
                        return false;
                });
                
                var td = tr.children("td:eq(" + (ind + 1) + ")");
                td.html('<div class="subTask" draggable="true" data-id="' + ttEvents[t].id + '" data-name="' + ttEvents[t].name + '" data-address="' + ttEvents[t].address + '" data-lat="' + ttEvents[t].lat + '" data-lng="' + ttEvents[t].lng + '" data-customer="' + ttEvents[t].customer + '" data-resource="' + ttEvents[t].resource + '" data-start="' + ttEvents[t].start + '" data-end="' + ttEvents[t].end + '" data-toggle="tooltip" data-placement="top" title="' + ttEvents[t].name + '"><span class="text">' + /*shortenText(ttEvents[t].name, len)*/ ttEvents[t].name + '</span> <a href="#"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></a> <div class="ttDrag" draggable="false"></div></div>');
                td.attr("colspan", len);
                
                for(var i = 0; i < len - 1; i++)
                    td.next().remove();
                
                calcWidth(td.children(".subTask")); // calc right here!
                
                markers.push($.makeArray({ "lat": ttEvents[t].lat, "lng": ttEvents[t].lng, "title": ttEvents[t].name, "task": true, "taskID": ttEvents[t].id }));
            }
            
            $('[data-toggle="tooltip"]').tooltip(); // tooltip bootstrap
            
            // remove duplicity markers
            var uMarkers = [];
            
            $.each(markers, function(index, element)
            {
                if($.inArray(element, uMarkers) === -1)
                    uMarkers.push(element);
            });
            
            markers = uMarkers;
            
            this.clearMarkers();
        },
        addMarker: function(title, lat, lng)
        {
            var marker = new google.maps.Marker(
            {
                map: map,
                title: title,
                position: new google.maps.LatLng(lat, lng),
                task: false,
                taskID: undefined
            });
            
            markersI.push(marker);
        },
        clearMarkers: function()
        {
            for(var i = 0; i < markersI.length; i++)
                markersI[i].setMap(null);
            
            markersI = [];
        },
        closeDetails: function()
        {
            //$("#ttDetail").hide();
            
            if(infoWindow)
                infoWindow.close();
        },
        getColumns: function()
        {
            return (this.options.lastHour - this.options.firstHour + 1) * (1 / this.options.accuracy);
        },
        getTimeUnit: function()
        {
            return this.options.timeUnit / this.options.accuracy;
        },
        getMap: function()
        {
            return map;
        },
        refresh: function(resources, events, datetime)
        {
            ttEvents    = events;
            ttResources = resources;
            date        = moment(datetime);
            
            this._load(date);
            this.showMarkers();
        },
        options: function(key, value)
        {
            if(!value)
                return this.options[key];
            
            this.options[key] = value;
        },
        /*showDetails: function(name, address, customer)
        {
            $("#ttDetailName").text(name);
            $("#ttDetailAddress").text(address);
            $("#ttDetailCustomer").text(customer);
            
            $("#ttDetail").show();
        },*/
        showDetails: function(marker, event, resource)
        {
            var contentString = '<div id="content" style="min-width:300px">'+
              '<div id="siteNotice">'+
              '</div>'+
              '<h1 id="firstHeading" class="firstHeading">' + event.name + '</h1>'+
              '<div id="bodyContent">'+
                '<form class="form-horizontal">' +
                    '<div class="form-group">' +
                        '<label class="col-sm-4 control-label">Název</label>' +
                        '<div class="col-sm-8"><p class="form-control-static">' + event.name + '</p></div>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label class="col-sm-4 control-label">Adresa</label>' +
                        '<div class="col-sm-8"><p class="form-control-static">' + event.address + '</p></div>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label class="col-sm-4 control-label">Zákazník</label>' +
                        '<div class="col-sm-8"><p class="form-control-static">' + event.customer + '</p></div>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label class="col-sm-4 control-label">Zdroj</label>' +
                        '<div class="col-sm-8"><p class="form-control-static">' + resource + '</p></div>' +
                    '</div>' +
                    '<div class="form-group">' +
                        '<label class="col-sm-4 control-label">Čas</label>' +
                        '<div class="col-sm-8"><p class="form-control-static">' + (event.start == null ? "" : event.start) + ' - ' + (event.end == null ? "" : event.end) + '</p></div>' +
                    '</div>' +
                '</form>' +
              '</div>'+
              '</div>';
            
            infoWindow = new google.maps.InfoWindow(
            {
                content: contentString
            });
            infoWindow.open(map, marker);
        },
        showMarkers: function()
        {
            var $this = this;
            
            setTimeout(function()
            {
                if(map == null)
                    $this.showMarkers();
                else
                    $this._showMarkers();
            }, 500);
        },
        _showMarkers: function()
        {
            var $this = this;
            
            for(var i = 0; i < markers.length; i++)
            {
                var label = markers[i][0].task ? '<span class="map-icon map-icon-general-contractor"></span>' : '<span class="map-icon map-icon-male"></span>';
                
                var marker = new Marker(//new google.maps.Marker(
                {
                    map: map,
                    title: markers[i][0].title,
                    position: new google.maps.LatLng(markers[i][0].lat, markers[i][0].lng),
                    task: markers[i][0].task,
                    taskID: markers[i][0].taskID,
                    icon:
                    {
                        path: MAP_PIN,
                        fillColor: markers[i][0].task ? '#4CD964' : "#FF3A2D",
                        fillOpacity: 0.8,
                        strokeColor: '',
                        strokeWeight: 0
                    },
                    map_icon_label: label
                });
                
                google.maps.event.addListener(marker, "click", function()
                {
                    if(infoWindow)
                        infoWindow.close();
                    
                    if(this.task)
                    {
                        for(var t = 0; t < ttEvents.length; t++)
                        {
                            if(ttEvents[t].id != this.taskID)
                                continue;

                            var customer = ttEvents[t].customer;

                            if(ttEvents[t].resource != null)
                                customer = $("#ttTable tr td[data-id='" + ttEvents[t].resource + "']:first-child").text();

                            $this.showDetails(this, ttEvents[t], customer);

                            break;
                        }
                    }
                });
                
                markersI.push(marker);
            }
            
            this.zoomOut();
        },
        zoomOut: function()
        {
            var bounds = new google.maps.LatLngBounds();
            
            for(var i = 0; i < markersI.length; i++)
                bounds.extend(markersI[i].getPosition());
            
            map.fitBounds(bounds);
        }
    }
    
    timeTable.defaults = 
    {
        accuracy: 1, // 0.25, 0.5, 1
        timeUnit: 1,
        dateFormat: "YYYY-MM-DD HH:mm:ss",
        firstHour: 8,
        lastHour: 18,
        minMapHeight: 300,
        zoomOnClick: false,
        resources: null, // customers
        events: null, // tasks
        onUpdate: null, // function(task)
        onDateChange: null // function(old, new)
    }

    window.timeTable = function(container, options)
    {
        var tTable = new timeTable($(container), options);
        tTable._init();
        
        return tTable;
    };

    $.fn.timeTable = function(options)
    {
        return window.timeTable($(this), options);
    }
}((window.jQuery), window, document));