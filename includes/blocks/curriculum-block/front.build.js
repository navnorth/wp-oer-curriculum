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
        var selpertxt = oercurr__t('Show') +': '+ val;   
        jQuery("#"+bid).attr('selper',val);
        lpCurSaveToLocalAttribute(bid, "selper", val, selpertxt);
        var target = jQuery(this).parent();
        resetSelection(target);
    });  
    jQuery(document).on('keydown','.oercurr-blk-topbar-display-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var keycode = (e.keyCode ? e.keyCode : e.which);
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        var val = jQuery(this).attr('ret');
        var selpertxt = oercurr__t('Show') +': '+ val;
        jQuery("#"+bid).attr('selper',val);
        var target = jQuery(this).parent();
        lpCurSaveToLocalAttribute(bid, "selper", val, selpertxt, keycode, target);
    });
    
    
    
    jQuery(document).on('click','.oercurr-blk-topbar-sort-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var val = jQuery(this).attr('ret');
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        jQuery("#"+bid).attr('selsrt',val);
        var selsrttxt = oercurr__t('Sort By') +': '+ jQuery(this).text();
        lpCurSaveToLocalAttribute(bid, "selsrt", val, selsrttxt);
        var target = jQuery(this).parent();
        resetSelection(target);
    });
    jQuery(document).on('keydown','.oercurr-blk-topbar-sort-option li a',function(e){
        e.preventDefault ? e.preventDefault() : e.returnValue = false;
        var keycode = (e.keyCode ? e.keyCode : e.which);
        var bid = jQuery(this).parents('.oercurr-blk-main').attr('blockid');
        var val = jQuery(this).attr('ret');
        jQuery("#"+bid).attr('selsrt',val);
        var selsrttxt = oercurr__t('Sort By') +': '+ jQuery(this).text();
        var target = jQuery(this).parent();
        lpCurSaveToLocalAttribute(bid, "selsrt", val, selsrttxt, keycode, target);
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
    
    var oercurr_blk_selcat = instanceparent.attr('selcat');
    var oercurr_blk_selper = instanceparent.attr('selper');
    var oercurr_blk_selsrt = instanceparent.attr('selsrt');
    
    var dta = {
  		'action' 	 : 'oercurr_cb_rebuild_post_block',
  		'sel'      : oercurr_blk_selcat,
  		'per'      : oercurr_blk_selper,
  		'srt'      : oercurr_blk_selsrt,
  	};
    //console.log('SEL CAT:'+oercurr_blk_selcat);
    //console.log('SEL PER:'+oercurr_blk_selper);
    //console.log('SEL SRT:'+oercurr_blk_selsrt);
    jQuery.ajax({
  		type:'POST',
  		url: curriculum_block_ajax_object.ajaxurl,
  		data: dta,
  		success:function(response){
        response = JSON.parse(response);
        var instance = jQuery('[blockid="'+bid+'"]');
          if(typeof cgbGlobal == 'undefined'){ 
            instanceparent.find('#oercurr-blk-content_drop').html(response['data']);
            instanceparent.find('.oercurr-blk-topbar-left span').text(curriculum_block_ajax_object['Browse All']+' '+response['cnt']+' '+curriculum_block_ajax_object['Curriculums']);
          }
      
          setTimeout(function(){
            instanceparent.find('.lp_cur_blk_content_preloader_table').hide(300);
          }, 300);
          
  		},
  		error: function(XMLHttpRequest, textStatus, errorThrown) {
        setTimeout(function(){
          //console.log('close preloader2');
          instanceparent.find('.lp_cur_blk_content_preloader_table').hide(300);
        }, 300);
  		}
  	});
  
}


function lpCurSaveToLocalAttribute(bid, typ, val, disp=null, kcode=null, target=null){

  if(kcode==null){ //click
      
      if(typ == 'selper'){
        jQuery("#"+bid).find('.oercurr-blk-topbar-display-text span').text(disp);
      }else{
        jQuery("#"+bid).find('.oercurr-blk-topbar-sort-text span').text(disp);
      }
      
  }else{
    if(kcode == '13'){ 
      if(typ == 'selper'){
        jQuery("#"+bid).find('.oercurr-blk-topbar-display-text span').text(disp);
      }else{
        jQuery("#"+bid).find('.oercurr-blk-topbar-sort-text span').text(disp);
      }
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