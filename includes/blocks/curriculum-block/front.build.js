jQuery( document ).ready(function() {
  
  
  

    // DISPLAY BOX
    jQuery(document).on('click','.oercurr-blk-topbar-display-text>a',function(e){
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
      var target = jQuery(this).parent().siblings('ul');
      lpCurTogleOptions(target); 
    })  
    jQuery(document).on('keydown','.oercurr-blk-topbar-display-text>a',function(e){
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if(keycode != '9') {// other than tab
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
      var target = jQuery(this).parent().siblings('ul');
      lpCurTogleOptions(target,keycode);
    }
    });

    
        
    // SORT BOX
    jQuery(document).on('click','.oercurr-blk-topbar-sort-text>a',function(e){
      e.preventDefault ? e.preventDefault() : e.returnValue = false;
      var target = jQuery(this).parent().siblings('ul');
      lpCurTogleOptions(target); 
    })
    jQuery(document).on('keydown','.oercurr-blk-topbar-sort-text>a',function(e){
      var keycode = (e.keyCode ? e.keyCode : e.which);
      if(keycode != '9') {// other than tab
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var target = jQuery(this).parent().siblings('ul');
        lpCurTogleOptions(target,keycode);
      }
    });

   
    
    // SUB MENU EVENTS
    jQuery(document).on('click','.oercurr-blk-topbar-display-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        var val = jQuery(this).attr('ret');      
        lpCurSaveToLocalAttribute("postsPerPage-"+bid, val);
        var target = jQuery(this).parent();
        resetSelection(target);
    });  
    jQuery(document).on('keydown','.oercurr-blk-topbar-display-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var keycode = (e.keyCode ? e.keyCode : e.which);
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        var val = jQuery(this).attr('ret');
        var target = jQuery(this).parent();
        lpCurSaveToLocalAttribute("postsPerPage-"+bid, val, keycode, target);
    });
    
    
    
    jQuery(document).on('click','.oercurr-blk-topbar-sort-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var val = jQuery(this).attr('ret');
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        lpCurSaveToLocalAttribute("sortBy-"+bid, val);
        var target = jQuery(this).parent();
        resetSelection(target);
    });
    jQuery(document).on('keydown','.oercurr-blk-topbar-sort-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var keycode = (e.keyCode ? e.keyCode : e.which);
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        var val = jQuery(this).attr('ret');
        var target = jQuery(this).parent();
        lpCurSaveToLocalAttribute("sortBy-"+bid, val, keycode, target);
    });
  
    
});


function resetSelection(target){
  target.siblings('li').removeClass('selected');
  target.addClass('selected');
  target.parent('ul').hide(300);
  target.parent('ul').siblings('div').find('a').focus();
  var instanceparent = target.parents('.oercurr-blk-main');
  updatepostdisplay(instanceparent);
}


function updatepostdisplay(instanceparent){
    
    var bid = instanceparent.attr('blockid');
    instanceparent.find('.lp_cur_blk_content_preloader_table').show(300);
    var dta = {
  		'action' 	 : 'oercurr_cb_rebuild_post_block',
  		'sel'      : localStorage.getItem('selectedCategory-'+bid),
  		'per'      : localStorage.getItem('postsPerPage-'+bid),
  		'srt'      : localStorage.getItem('sortBy-'+bid),
  	};
    //console.log('SEL CAT:'+localStorage.getItem('selectedCategory-'+bid));
    //console.log('SEL PER:'+localStorage.getItem('postsPerPage-'+bid));
    //console.log('SEL SRT:'+localStorage.getItem('sortBy-'+bid));
    jQuery.ajax({
  		type:'POST',
  		url: curriculum_block_ajax_object.ajaxurl,
  		data: dta,
  		success:function(response){
  			//console.log(response);
        response = JSON.parse(response);
        var instance = jQuery('[blockid="'+bid+'"]');
          if(typeof cgbGlobal == 'undefined'){ 
            instanceparent.find('#oercurr-blk-content_drop').html(response['data']);
            instanceparent.find('.oercurr-blk-topbar-display-text span').text('show : '+localStorage.getItem('postsPerPage-'+bid));
            instanceparent.find('.oercurr-blk-topbar-sort-text span').text('Sort by: '+localStorage.getItem('sortBy-'+bid));
            instanceparent.find('.oercurr-blk-topbar-left span').text('Browse All '+response['cnt']+' Curriculums');
          }
      
          setTimeout(function(){
            instanceparent.find('.lp_cur_blk_content_preloader_table').hide(300);
          }, 300);
          
  		},
  		error: function(XMLHttpRequest, textStatus, errorThrown) {
        setTimeout(function(){
          console.log('close preloader2');
          instanceparent.find('.lp_cur_blk_content_preloader_table').hide(300);
        }, 300);
  		}
  	});
  
}


function lpCurSaveToLocalAttribute(key, val, kcode=null, target=null){
  if(kcode==null){ //click
    
      localStorage.setItem(key, val);
      
  }else{
    if(kcode == '13'){ 
      localStorage.setItem(key, val); 
      if(target != 'null'){
        resetSelection(target);
      }
    }else if(kcode == '38'){ //up 
      if(target != 'null'){
        target.prev('li').find('a').focus();
        if(target.is(':first-of-type')){
          target.parent('ul').siblings('div').find('a').focus();
        }
      }
    }else if(kcode == '40'){ //down 
      if(target != 'null'){
        target.next('li').find('a').focus();
      }
    }
  }
  //console.log(localStorage.getItem(key));
}

function lpCurTogleOptions(target, kcode = 13){
  if(kcode == '32' || kcode == '13'){ // space and enter
    lpCurShowHide(target) 
  }else if(kcode == '38'){ // up
    target.children('li.selected').find('a').focus();
  }else if(kcode == '40'){ // down
    target.children('li.selected').find('a').focus();
  }
}

function lpCurShowHide(target){
  if(target.is(':visible')){
    target.hide(300);
  }else{
    jQuery('.oercurr-blk-topbar-option').hide(300);
    target.show(300);  
  } 
}