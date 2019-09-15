$(document).ready(function () {
	/*Sales Statics chart*/
	
    $(function () {
    // 横轴 数据
    var defaultLocaleWeekdays = 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split('_');
    //defaultLocaleWeekdaysShort = 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_');
    //defaultLocaleWeekdaysMin = 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_');
    //defaultLocaleMonths = 'January_February_March_April_May_June_July_August_September_October_November_December'.split('_');
    //defaultLocaleMonthsShort = 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_');
    var sharpLineData = {

        labels: defaultLocaleWeekdays,
        datasets: [
            {
                label: "Example dataset",
                fillColor: "rgba(3,169,243,0.7)",
                strokeColor: "rgba(23,112,233,0.7)",
                pointColor: "rgba(23,112,233,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(23,112,233,1)",
                data: [30, 55, 45, 100, 55, 30, 60]  // 图表上的数据
            }
        ]
    };

    var sharpLineOptions = {
	scaleShowGridLines: true,
	scaleGridLineColor: "rgba(0,0,0,.00)",
	scaleGridLineWidth: 1,
	bezierCurve: false,
	pointDot: true,
	pointDotRadius: 4,
	pointDotStrokeWidth: 1,
	pointHitDetectionRadius: 20,
	datasetStroke: false,
	datasetStrokeWidth: 1,
	datasetFill: true,
	responsive: true,
	resize: true
    };

    var ele
    if(!document.getElementById("sharpLinechart"))
    {
        ele=window.parent.document.getElementById("sharpLinechart");
    }else
    {
        ele=document.getElementById("sharpLinechart")
    }
  //  console.log(ele); null
    if(!ele)
        return;
    var ctx = ele.getContext("2d");
    var myNewChart = new Chart(ctx).Line(sharpLineData, sharpLineOptions);

    });
});
		
