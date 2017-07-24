$(window).bind("mousewheel", function (e) {
		console.log($(this).scrollTop()+" + "+$(this).height()+" = "+($(document).height()));
    if ($(this).scrollTop() + $(window).height() == $(document).height()) {
    	logScroll($("#scroll"), $("#scroll").attr("start"));
		}
	
});
			function logScroll(list, start){
				var value = 'start='+start;
				$.post(addressLogScroll, value, function(send){
					if(send.state){
						if(send.data){
							var logs = send.data.array;
							var html = '';
							for(var i = 0; i < logs.length; i++){
								html += addElement(logs[i]);
							}
							list.append(html);
							list.attr("start", send.data.start);
						}
					}
				}, 'JSON');
			}
			function addElement(element){
				var html = '<li>';
					html += '<div>';
					html += '<img class="log-picture" src="'+images+element.image+'">';
					html += '</div>';
					html += '<div>';
					if(element.reg.link != ''){
						html += '<p>';
						html += '<a href="'+element.reg.link+'">'+element.reg.title+'</a>';
						html += '</p>';
						html += '<p>'+element.reg.title+', '+element.timeEvent+'</p>';
					}else{
						html += '<p>';
						html += element.reg.title;
						html += '</p>';
						html += '<p>'+element.reg.title+', '+element.timeEvent+'</p>';
					}
					
					if(element.comment != ''){
						html += '<div class="comments">';
						html += '<img src="'+images+element.comment.image+'">';
						html += '<p>';
						html += '<span>'+element.comment.name+'</span>';
						html += element.comment.comment;
						html += '</p>';
						html += '</div>';
					}
					html += '</div>';
					html += '</li>';
					return html;
			}