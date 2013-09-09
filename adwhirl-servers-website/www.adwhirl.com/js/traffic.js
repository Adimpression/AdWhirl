var traffic = function() {
	var sum = 0;

	var me = {
		changed: false,
		getSum: function() {
			var new_sum = 0;
			$(".traffic").each(function() {
				var tmp = parseInt($(this).attr("value"));
				if (tmp>=0) new_sum+= tmp;
			});
			return new_sum;		
		},
		 activateSave: function () {
			me.changed=true;
			$("#save").removeClass("disabled").parent().removeClass("disabled");
		  $("#cancel").removeClass("disabled");
			$("#cancel").unbind("click");
		},
		setSumOnly: function() {
			$("#sum").text(me.getSum()+"%");	
		},
		setSum: function() {
			var new_sum=me.getSum();
			if (new_sum!=sum) {
				sum = new_sum;
				me.activateSave();
			}
			$("#sum").text(sum+"%");
		}
	};
	return me;
}();
