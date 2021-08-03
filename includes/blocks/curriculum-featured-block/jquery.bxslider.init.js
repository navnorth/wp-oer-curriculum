/* FEAT CURR BLK */
jQuery(window).on('load', function() {
  
  jQuery('.oercurr_cfb_right_featuredwpr').each(function(i, obj) {
    let cfb_blkid = jQuery(this).find('ul.featuredwpr_bxslider').attr('blk');
    let oercurr_cfb_minslides = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-minslides");
    let oercurr_cfb_maxslides = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-maxslides");
    let oercurr_cfb_moveslides = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-moveslides");
    let oercurr_cfb_slidewidth = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-slidewidth");
    let oercurr_cfb_slidemargin = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-slidemargin");
    let oercurr_cfb_slidealign = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-slidealign");
    let oercurr_cfb_slidedesclength = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-slidedesclength");
    let oercurr_cfb_slideimageheight = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-slideimageheight");
    
    console.log('minslides:'+oercurr_cfb_minslides);
    console.log('maxslides:'+oercurr_cfb_maxslides);
    console.log('moveslides:'+oercurr_cfb_moveslides);
    console.log('slidewidth:'+oercurr_cfb_slidewidth);
    console.log('slidemargin:'+oercurr_cfb_slidemargin);
    console.log('slidealign:'+oercurr_cfb_slidealign);
    console.log('slidedesclength:'+oercurr_cfb_slidedesclength);
    console.log('slideimageheight:'+oercurr_cfb_slideimageheight);
    

    jQuery('.featuredwpr_bxslider_'+cfb_blkid).bxSlider({
      minSlides: parseInt(oercurr_cfb_minslides),
      maxSlides: parseInt(oercurr_cfb_maxslides),
      moveSlides: parseInt(oercurr_cfb_moveslides),
      slideWidth: parseInt(oercurr_cfb_slidewidth),
      slideMargin: parseInt(oercurr_cfb_slidemargin),
      pager: false,    
      onSliderLoad: function(currentIndex) {
        jQuery(".featuredwpr_bxslider_"+cfb_blkid).css({"visibility":"visible","height":"auto"});
        /*
        if(oercurr_cfb_slidealign){
          if(oercurr_cfb_slidealign == 'left'){
            jQuery(".featuredwpr_bxslider_"+cfb_blkid).parent(".bx-viewport").parent(".bx-wrapper").css({"margin-left":"0px"});
          }else if(oercurr_cfb_slidealign == 'right'){
            jQuery(".featuredwpr_bxslider_"+cfb_blkid).parent(".bx-viewport").parent(".bx-wrapper").css({"margin-right":"0px"});
          }
        }else{
          jQuery(".featuredwpr_bxslider_"+cfb_blkid).parent(".bx-viewport").parent(".bx-wrapper").css({"margin-left":"0px"});
        }
        
        let dtc = jQuery(".curriculum-feat-title_"+cfb_blkid).detach();
        jQuery(dtc).insertBefore(jQuery(".featuredwpr_bxslider_"+cfb_blkid).parent(".bx-viewport"));
        
        let imgwidth = localStorage.getItem("lpInspectorFeatSliderSetting-"+cfb_blkid+"-slideimageheight");
        jQuery(".featuredwpr_bxslider_"+cfb_blkid+" li div.frtdsnglwpr a div.img img").css({"height":"100%", "max-height": oercurr_cfb_slideimageheight+"px", "max-width":"100%" });
        
        let sldcnt = jQuery(".featuredwpr_bxslider_"+cfb_blkid).find("li").length;
        $_sngsldmgn = (typeof oercurr_cfb_slidemargin === 'undefined')? 20 : oercurr_cfb_slidemargin;
        //jQuery(".featuredwpr_bxslider_"+cfb_blkid).find("li").css({"margin-right":$_sngsldmgn+'px'});
        
        $_sngsldwdt = (typeof oercurr_cfb_slidewidth === 'undefined')? (375 + $_sngsldmgn) : (oercurr_cfb_slidewidth + $_sngsldmgn);
        let whlsldwdt = sldcnt * $_sngsldwdt;
        jQuery(".featuredwpr_bxslider_"+cfb_blkid).css("width",whlsldwdt);  
        */
      }
      
    });
    
    
    
  });

  
  
    //localStorage.getItem("lpInspectorFeatSliderSetting-'.$attributes['blockid'].'-slideimageheight");
    //jQuery('.featuredwpr_bxslider_'+attributes.blockid).bxSlider();
})