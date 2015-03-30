var vc=0;
function toInitTotalNums(){
	if(baseurl!="")
	$.ajax({  	
			dataType:  'json',//type:'POST',
			data : "url="+baseurl+"&query="+count,
			success: function(data){
				if(data)
				{
				if(vc>50){
					clearInterval(time);
					$('#progress').prepend("<font color='#F0C'>处理结束,正在生产打包!</font><br/>")
					return;
					};
				count+=data.length;
				if(data.length>0)vc=0;else vc++;
				for(i in data)
				$('#progress').prepend( "<a  onmouseout = 'bb(this)'  onmouseover='aa(event || window.event,this)' href='"+data[i]+"' target='_blank' >"+"<font color='#0000FF'>"+data[i]+"</font></a><br/>");
				}
			    }
	});	
}
   var $_ = function (id) {
        return "string" == typeof id && document.getElementById(id);
    };
   function tg(a) {
        return /\.(bmp|gif|jpg|jpeg|png|BMP|JPEG|GIF|JPG|PNG)$/.test(a);
    };
	    var Tg = function (name) {
        return "string" == typeof name && document.getElementsByTagName(name);
    };

	   function isIe() {
        return navigator.userAgent.toUpperCase().indexOf("MSIE") != -1 ? true : false;
    }
function aa(event, ele) {
        e = event || window.event;
        var a = ele || this;
        if (!tg(a.href)) return;
        if (!e.x) { event.x = event.clientX; event.y = event.clientY };


        var href =  a.href;

        if ($_(a.href) == null) {
            var msg = document.createElement("div");
            msg.id = a.href;

            var innerT = "<img src=\"href\" />";
            msg.innerHTML = innerT.replace("href", href);
			e.yy=e.y;
			if(isIe())e.yy=parseInt(e.y)-parseInt(((document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body).scrollTop);
            with (msg.style) {
                position = "fixed";
                left = (e.l ? e.l : (parseInt(document.body.clientWidth) / 2 - parseInt(e.x) < 0) ? document.body.scrollLeft : parseInt(e.x) - (-5)) + "px";
                top = (e.t ? e.t : (parseInt(document.documentElement.clientHeight) / 2 - parseInt(e.yy) < 0) ? document.body.scrollTop : parseInt(document.body.scrollTop) - (-parseInt(e.yy)) - (-5)) + "px";
                margin = "2px";
                padding = "2px";
                border = "1px solid #cccccc";
            }
            document.body.appendChild(msg);
			
        } else {
			

            $_(a.href).style.visibility = "visible";
			
        }


    }
    function bb(e) {
		
        var a = this.nodeName=="A"?this:e;
		if($_(a.href))
        $_(a.href).style.visibility = "hidden";

    };
	

     var A = Tg("a");
    for (var i = 0; i < A.length; i++) {
        // onmouseout = "bb(this)";  //onmouseover="aa(this,event || window.event)"
        if (A[i].addEventListener) {
            A[i].addEventListener('onmouseover', aa, false);
            A[i].addEventListener('onmouseout', bb, false);
        } //W3C
        A[i].onmouseover = aa;
        A[i].onmouseout = bb;
    }