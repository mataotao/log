/**
 * 如果this被选中，则复选框为name的全部选中，否则反选
 * @param obj 当前操作复选框
 * @param name 所取的name名
 */
function selectAll(obj, name) {
	//prop()函数用于设置或返回当前jQuery对象所匹配的元素的属性值
	if($(obj).prop("checked") == true){
		$('input[name="'+name+'"]').prop("checked", true);

	}else{
		$('input[name="'+name+'"]').prop("checked", false);

	}
}


// 批量删除操作
function delall(){
	var arr = [];
	var key = 0;
	$('input[type=checkbox]').each(function(i){
		if($(this).attr('class') != 'checkall'){
			if($(this).prop('checked') == true){
				key++;
				arr[key] = $(this).val();
			}
		}
	});

	if(arr.length > 0){
		layer.confirm('您确定删除？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var delete_url = $('#delete').attr('url')
			window.location.href = delete_url+"?idArr="+arr;
		});
	}else{
		layer.alert('没有勾选任何选项！',-1);
	}
};

//未来之星
function wlzxdelall(){
	var arr = [];
	var key = 0;
	$('input[type=checkbox]').each(function(i){
		if($(this).attr('class') != 'checkall'){
			if($(this).prop('checked') == true){
				key++;
				arr[key] = $(this).val();
			}
		}
	});

	if(arr.length > 0){
		layer.confirm('您确定删除？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var delete_url = $('#wlzxdelete').attr('wlzxurl')
			window.location.href = delete_url+"?idArr="+arr;
		});
	}else{
		layer.alert('没有勾选任何选项！',-1);
	}
};


//天天牛股的批量删除
function servicedelall(){

	var arr = [];
	var key = 0;
	$('input[type=checkbox]').each(function(i){
		if($(this).attr('class') != 'checkall'){
			if($(this).prop('checked') == true){
				key++;
				arr[key] = $(this).val();
			}
		}
	});

	if(arr.length > 0){
		layer.confirm('删除后前端无法查看此内容,确认删除此内容？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var delete_url = $('#delete').attr('url')
			window.location.href = delete_url+"?arrasc_id="+arr;
		});
	}else{
		layer.alert('没有勾选任何选项！',-1);
	}
};
//未来之星的批量删除服务包
function wlzxservicedelall(){

	var arr = [];
	var key = 0;
	$('input[type=checkbox]').each(function(i){
		if($(this).attr('class') != 'checkall'){
			if($(this).prop('checked') == true){
				key++;
				arr[key] = $(this).val();
			}
		}
	});

	if(arr.length > 0){
		layer.confirm('删除后前端无法查看此内容,确认删除此内容？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var delete_url = $('#delete').attr('url')
			window.location.href = delete_url+"?idArr="+arr;
		});
	}else{
		layer.alert('没有勾选任何选项！',-1);
	}
};

//未来之星的前置删除
function wlzxQzdelall(){

	var arr = [];
	var key = 0;
	$('input[type=checkbox]').each(function(i){
		if($(this).attr('class') != 'checkall'){
			if($(this).prop('checked') == true){
				key++;
				arr[key] = $(this).val();
			}
		}
	});

	if(arr.length > 0){
		layer.confirm('确认删除此内容？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var delete_url = $('#delete').attr('url')
			window.location.href = delete_url+"?idArr="+arr;
		});
	}else{
		layer.alert('没有勾选任何选项！',-1);
	}
};
//首席月报的删除
function monthdelall(){

	var arr = [];
	var key = 0;
	$('input[type=checkbox]').each(function(i){
		if($(this).attr('class') != 'checkall'){
			if($(this).prop('checked') == true){
				key++;
				arr[key] = $(this).val();
			}
		}
	});

	if(arr.length > 0){
		layer.confirm('删除后前端无法查看此内容,确认删除此内容？', {
			btn: ['确定','取消'] //按钮
		}, function(){
			var service_type = $('#delete').attr('service_type');
			var delete_url = $('#delete').attr('url')
			window.location.href = delete_url+"?idArr="+arr+"&service_type="+service_type;
		});
	}else{
		layer.alert('没有勾选任何选项！',-1);
	}
};






















