$(function(){
	$.fn.hasAttr = function(attribute){
		var attr = this.attr(attribute);
		if (typeof attr !== typeof undefined && attr !== false) 
		   return true;
		return false;
	}

	$.fn.materialForm = function() {
		// Inputs
	    this.find('input, textarea').each(function(i){
	    	if(isValidType($(this))){
	    		var name = $(this).attr('name');
	    		$(this).attr('id', name);
		    	var $wrap = $(this).wrap("<div class='material-input'></div>").parent();
		    	$wrap.append("<span class='material-bar'></span>");

		    	var tagName = $(this).prop("tagName").toLowerCase();
		    	$wrap.addClass(tagName);
		    	
		    	var placeholder = $(this).attr('placeholder');
		    	if(placeholder){
		    		$wrap.append("<label for='"+name+"'>"+placeholder+"</label>");
		    		$(this).removeAttr('placeholder');
		    	}

		    	addFilled($(this));
	    	}

	    	if(isType($(this), 'radio')/* || isType($(this), 'checkbox'*/){
	    		
	    		var name = $(this).attr('name').replace('[]','');
	    		var group_id = 'material-group-' + name;
	    		var placeholder = $(this).attr('placeholder');
	    		var item_id = name+ '-' + i;
	    		var $label = $('<label for="'+item_id+'">'+placeholder+'</label>');
	    		var $group_item = $('<div class="material-group-item"></div>');
	    		$(this).attr('id', item_id);

	    		if($("#"+group_id).length){
	    			var $group = $('#'+group_id);
	    			$group.append($(this));
	    		}
	    		else{
	    			var $group = $(this).wrap("<div class='material-group' id='"+group_id+"'></div>").parent();
	    		}

	    		if(isType($(this), 'radio'))
	    			var $radio = $('<div class="material-radio"></div>');
	    		else
	    			var $radio = $('<div class="material-checkbox"></div>');

	    		$group_item.append($(this));
	    		$group_item.append($label);
	    		$group_item.append($radio);

	    		$group.append($group_item);
	    	}
	    });

	    this.find('input, textarea').on('blur', function(){
		    if(isValidType($(this)))
		    	addFilled($(this))
	    });

	    // Radio

	    function isValidType($el){
	    	var type = $el.attr('type');
	    	return (type != 'hidden' && type != 'submit' && type != 'checkbox' && type != 'radio' && type != 'file' ? 1 : 0);
	    }

	    function isType($el, type){
	    	var el_type = $el.attr('type');
	    	return (el_type == type);
	    }

	    function addFilled($el){
	    	if($el.val())
	    		$el.addClass('filled');
	    	else
	    		$el.removeClass('filled');
	    }

	};
});