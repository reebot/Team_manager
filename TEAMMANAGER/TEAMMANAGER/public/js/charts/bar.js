$(function () {
	var data = new Array ();
    var ds = new Array();
	
	data.push ([[1,77],[2,34],[3,37],[4,45],[5,56]]);
 
    for (var i=0, j=data.length; i<j; i++) {
    	
	     ds.push({
	        data:data[i],
	        grid:{
            hoverable:true
        },
	        bars: {
	            show: true, 
	            barWidth: 0.2, 
	            order: 1,
	            lineWidth: 0.5, 
				fillColor: { colors: [ { opacity: 0.65 }, { opacity: 1 } ] }
	        }
	    });
	}
	    
    $.plot($("#bar-chart"), ds, {
    	colors: ["#2d65b2", "#222", "#666", "#BBB"]
                

    });
                

    
});