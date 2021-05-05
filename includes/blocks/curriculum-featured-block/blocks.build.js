const { __ } = wp.i18n; // Import __() from wp.i18n

const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;
const {
  CheckboxControl,
  RadioControl,
  TextControl,
  ToggleControl,
  SelectControl
} = wp.components;
let resources_arr = [];
let feats = [];
let poschangeinitiated = false;
let prevelem = "li";
const globalSettingOptions = [1, 2, 3, 4, 5];
const globalSettingMargin = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50];
const globalSettingAlign = ["middle", "left", "right"];
registerBlockType("oer-curriculum/block-curriculum-featured-block", {
  title: __("Curriculum Featured Block"),
  icon: "welcome-learn-more",
  category: "common",
  keywords: [
    __("curriculum-featured-block"),
    __("curriculum"),
    __("featured"),
  ],
  attributes: {
    blockwidth: {
      type: "intiger",
      default: 1170
    },
    blockid: {
      type: "string"
    },
    highlight: {
      type: "string",
      default: "resources"
    },
    selectedfeatured: {
      type: "string"
    },
    data: {
      type: "string"
    },
    resources: {
      type: "string"
    },
    curriculum: {
      type: "string"
    },
    blocktitle: {
      type: "string",
      default: "Featured"
    },
    //SEARCH
    searchstring: {
      type: "string"
    },
    //FILTERING
    resourcesubjects: {
      type: "object"
    },
    curriculumsubjects: {
      type: "object"
    },
    resourcesubjectfilter: {
      type: "string"
    },
    curriculumsubjectfilter: {
      type: "string"
    },
    filtertype: {
      type: "string"
    },
    //SETTINGS
    minslides: {
      type: "intiger",
      default: 1
    },
    maxslides: {
      type: "intiger",
      default: 3
    },
    moveslides: {
      type: "intiger",
      default: 1
    },
    slidewidth: {
      type: "intiger",
      default: 375
    },
    slidemargin: {
      type: "intiger",
      default: 20
    },
    slidealign: {
      type: "string",
      default: "left"
    },
    slidedesclength: {
      type: "intiger",
      default: cgbGlobal["slidedesclength"]
    },
    slideimageheight: {
      type: "intiger",
      default: cgbGlobal["slideimageheight"]
    }
  },
  edit: function (props) {
    const attributes = props.attributes;
    const setAttributes = props.setAttributes; //console.log('BLOCK ID: '+attributes.blockid);
    //SET BLOCK INSTANCE IDS

    let featblockcount = 0;
    let blkidx = 0;
    const blocks = wp.data.select("core/block-editor").getBlocks(); //console.log('******************');

    blocks.map((val, index) => {
      if (val.name == "oer-curriculum/block-curriculum-featured-block") {
        var uniq = "cfb" + new Date().getTime();
        var cid = val.clientId;
        /*
        var attr = wp.data.select( 'core/block-editor' ).getBlockAttributes(cid)
        var blkid;;
        if(!attributes.blockid){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { blockid: uniq } )
            localStorage.setItem('curriculum-feat-blkid', uniq );
            blkid = uniq;
        }else{
            blkid = attributes.blockid;
        }
        */

        wp.data.dispatch("core/block-editor").updateBlockAttributes(cid, {
          blockid: cid
        });
        /*
        minslides
        maxslides
        moveslides
        slidewidth
        slidemargin
        */
        //set defaults

        /*
        if(attributes.minslides === undefined){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { minslides: 1 } )
        }
        if(attributes.maxslides === undefined){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { maxslides: 3 } )
        }
        if(attributes.moveslides === undefined){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { moveslides: 1 } )
        }
        console.log('UNDEFINED WIDTH: '+cid+' - '+attributes.slidewidth)
        if(attributes.slidewidth === undefined){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { slidewidth: 375 } )
        }
        if(attributes.slidemargin === undefined){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { slidemargin: 10 } )
        }
        */

        if (attributes.filtertype === undefined) {
          wp.data.dispatch("core/block-editor").updateBlockAttributes(cid, {
            filtertype: "search"
          });
        }
        /*
        if(attributes.blocktitle === undefined){
            wp.data.dispatch( 'core/block-editor' ).updateBlockAttributes( cid, { blocktitle: 'Featured' } )
            cgbGlobal['curriculum_feat_title_'+blkid] = 'Featured';
        }
        */
        //console.log('BLOCK '+blkid+ ' ('+blkidx+')');

        blkidx++;
        featblockcount++;
      }
    }); //console.log('******************');
    // RETURN MESSAGE WHILE CATEGORIES AND CURRICULUMS ARE NOT YET FULLY LOADED

    /*
    if(!attributes.blockid || 
      !attributes.minslides || 
      !attributes.maxslides || 
      !attributes.moveslides || 
      !attributes.slidewidth || 
      !attributes.slidemargin ||
      !attributes.filtertype){
      return 'Setting up blocks...'
    }
    */

    curriculumfeatslider_loadall(featblockcount); //convert comma delimitted to array

    let highlighted = [];

    if (typeof attributes.selectedfeatured !== "undefined") {
      const tmparr = attributes.selectedfeatured.split(",");
      tmparr.map((arr, index) => {
        const rowarr = arr.split("|");
        highlighted.push([parseInt(rowarr[0]), rowarr[1]]);
      });
    }

    let resources_arr = [];
    resources_arr = attributes.resources;
    /*
     0 - Resource taxonomy
     1 - Curriculum taxonomy
     2 - Resource List
     3 - Curriculum List
     */

    /* GET ALL DATA */

    if (!attributes.data) {
      //wp.apiFetch({ url: '/wp-json/curriculum/feat/dataquery' }).then(data =>{  setAttributes({resources: data })  })
      wp.apiFetch({
        url: "/wp-json/curriculum/feat/dataquery"
      }).then((data) => {
        setAttributes({
          data: data
        });
        setAttributes({
          resourcesubjects: data[0]
        });
        setAttributes({
          curriculumsubjects: data[1]
        });
        setAttributes({
          resources: data[2]
        });
        setAttributes({
          curriculum: data[3]
        }); //console.log(data[0]);
        //console.log(data[1]);
        //console.log(data[2]);
        //console.log(data[3]);
      });
    }

    if (!attributes.data) {
      return "Loading Featured Data...";
    }

    let data_arr = attributes.data;
    let res_subj_arr = data_arr[0];
    let cur_subj_arr = data_arr[1];
    let res_list_arr = data_arr[2];
    let cur_list_arr = data_arr[3];
    /* SET RESOURCES LIST ATTRIBUTES */

    /*
    if(!attributes.resources){
    wp.apiFetch({ url: '/wp-json/curriculum/feat/resourcequery' }).then(resources =>{  setAttributes({resources: resources })  })
    }
    if(!attributes.resources){ return 'Loading resources...'; }	
    let res_list_arr = [];
    res_list_arr = attributes.resources;
    */

    /* SET CURRICULUM LIST ATTRIBUTES */

    /*
      if(!attributes.curriculum){
    	wp.apiFetch({ url: '/wp-json/curriculum/feat/curriculumquery' }).then(resources =>{  setAttributes({curriculum: resources })  })
    }
    if(!attributes.curriculum){ return 'Loading curriculum...'; }
    let cur_list_arr = [];
      cur_list_arr = attributes.curriculum;
      */

    /* SET RESOURCE SUBJECTS LIST ATTRIBUTES */

    /*
    if(!attributes.resourcesubjects){
    wp.apiFetch({ url: '/wp-json/curriculum/feat/taxquery?posttype=resource' }).then(taxonimies =>{  setAttributes({resourcesubjects: taxonimies })  })
    }
    if(!attributes.resourcesubjects){ return 'Loading Subjects...'; }
    let res_subj_arr = [];
    res_subj_arr = attributes.resourcesubjects;
    */

    /* SET CURRICULUM SUBJECTS LIST ATTRIBUTES */

    /*
    if(!attributes.curriculumsubjects){
    wp.apiFetch({ url: '/wp-json/curriculum/feat/taxquery?posttype=oer-curriculum' }).then(taxonimies =>{  setAttributes({curriculumsubjects: taxonimies })  })
    }
    if(!attributes.curriculumsubjects){ return 'Loading Subjects...'; }
    let cur_subj_arr = [];
    cur_subj_arr = attributes.curriculumsubjects;
    */

    function updateHighlight(newValue, index) {
      if (cgbGlobal["bxresetblocked"]) {
        return;
      }

      const type = newValue.target.getAttribute("fet");

      if (newValue.target.checked) {
        const selfeat = newValue.target.getAttribute("data");
        highlighted.push([parseInt(selfeat), type]);
      } else {
        let todel = parseInt(newValue.target.getAttribute("data"));
        let ex = highlighted.findIndex(findMatch(todel));

        if (ex != -1) {
          highlighted.splice(ex, 1);
        }
      }

      feats = [];
      highlighted.map((feat, index) => {
        let obj;
        let idx;

        if (feat[1] == "cur") {
          obj = attributes.curriculum.find(
            (obj) => obj.id == parseInt(feat[0])
          );
          idx = attributes.curriculum.indexOf(parseInt(feat[0]));
        } else {
          obj = attributes.resources.find((obj) => obj.id == parseInt(feat[0]));
          idx = attributes.resources.indexOf(parseInt(feat[0]));
        }

        if (typeof obj != "undefined") {
          feats.push(Object.values(obj));
        }
      });
      let str = "";
      highlighted.map((hlite, index) => {
        if (hlite[0] !== undefined && hlite[1] !== undefined) {
          if (str == "") {
            str += hlite[0] + "|" + hlite[1];
          } else {
            str += "," + hlite[0] + "|" + hlite[1];
          }
        }
      });
      setAttributes({
        selectedfeatured: str
      });
      curriculumfeatslider_reset(attributes.blockid, 750, newValue.target);
    }

    function findMatch(todel) {
      return function (innerArr) {
        return innerArr[0] === todel;
      };
    } //console.log(attributes.resources);
    //console.log(attributes.selectedfeatured);
    //console.log(attributes.curriculum);
    //const feats = attributes.selectedfeatured.split(',');
    //console.log(highlighted);

    function updateposition() {
      prevelem = prevelem == "li" ? "div" : "li";
      highlighted = [];
      jQuery(".oer_curriculum_inspector_feat_hlite_node").each(function () {
        let h_id = jQuery(this).attr("data");
        let h_tp = jQuery(this).attr("typ");
        highlighted.push([parseInt(h_id), h_tp]);
      });
      feats = [];
      highlighted.map((feat, index) => {
        let obj;

        if (feat[1] == "cur") {
          obj = attributes.curriculum.find(
            (obj) => obj.id == parseInt(feat[0])
          );
          const idx = attributes.curriculum.indexOf(parseInt(feat[0]));
        } else {
          obj = attributes.resources.find((obj) => obj.id == parseInt(feat[0]));
          const idx = attributes.resources.indexOf(parseInt(feat[0]));
        }

        if (typeof obj != "undefined") {
          feats.push(Object.values(obj));
        }
      });
      let str = "";
      highlighted.map((hlite, index) => {
        if (hlite[0] !== undefined && hlite[1] !== undefined) {
          if (str == "") {
            str += hlite[0] + "|" + hlite[1];
          } else {
            str += "," + hlite[0] + "|" + hlite[1];
          }
        }
      });
      setAttributes({
        selectedfeatured: str
      });
    }

    function removefeatured() {
      highlighted = [];
      jQuery(".oer_curriculum_inspector_feat_hlite_node.stay").each(
        function () {
          let h_id = jQuery(this).attr("data");
          let h_tp = jQuery(this).attr("typ");
          highlighted.push([parseInt(h_id), h_tp]);
        }
      );
      feats = [];
      highlighted.map((feat, index) => {
        let obj;

        if (feat[1] == "cur") {
          obj = attributes.curriculum.find(
            (obj) => obj.id == parseInt(feat[0])
          );
          const idx = attributes.curriculum.indexOf(parseInt(feat[0]));
        } else {
          obj = attributes.resources.find((obj) => obj.id == parseInt(feat[0]));
          const idx = attributes.resources.indexOf(parseInt(feat[0]));
        }

        if (typeof obj != "undefined") {
          feats.push(Object.values(obj));
        }
      });
      let str = "";
      highlighted.map((hlite, index) => {
        if (hlite[0] !== undefined && hlite[1] !== undefined) {
          if (str == "") {
            str += hlite[0] + "|" + hlite[1];
          } else {
            str += "," + hlite[0] + "|" + hlite[1];
          }
        }
      });
      setAttributes({
        selectedfeatured: str
      });
    } //console.log(highlighted);
    //console.log(typeof attributes.selectedfeatured);

    if (typeof attributes.selectedfeatured != "undefined") {
      feats = [];
      highlighted.map((feat, index) => {
        let obj;

        if (feat[1] == "cur") {
          obj = attributes.curriculum.find(
            (obj) => obj.id == parseInt(feat[0])
          );
          const idx = attributes.curriculum.indexOf(parseInt(feat[0]));
        } else {
          obj = attributes.resources.find((obj) => obj.id == parseInt(feat[0]));
          const idx = attributes.resources.indexOf(parseInt(feat[0]));
        }

        if (typeof obj != "undefined") {
          feats.push(Object.values(obj));
        }
      });
    } else {
      feats = [];
    }

    function onInspectorLoad() {
      var featexist = setInterval(function () {
        if (jQuery(".oer_curriculum_inspector_feat_hlite_list").length) {
          clearInterval(featexist);
          setTimeout(function () {
            sort();
          }, 500);
        }
      }, 100); // check every 100ms
    }

    function onModalQuickButton(elem, index) {
      var type = elem.target.getAttribute("typ");

      if (type == "res") {
        jQuery(".oer_curriculum_inspector_feat_modal_resource_wrapper").hide(
          300,
          function () {
            jQuery(
              ".oer_curriculum_inspector_feat_modal_curriculum_wrapper"
            ).show(300);
          }
        );
      } else {
        jQuery(".oer_curriculum_inspector_feat_modal_curriculum_wrapper").hide(
          300,
          function () {
            jQuery(
              ".oer_curriculum_inspector_feat_modal_resource_wrapper"
            ).show(300);
          }
        );
      }
    }
    /*
    minslides
    maxslides
    moveslides
    slidewidth
    slidemargin
    */

    function onSettingChange(elem, index) {
      var type = elem.target.getAttribute("typ");
      var val = elem.target.value;

      switch (type) {
        case "minslides":
          setAttributes({
            minslides: parseInt(val)
          });
          break;

        case "maxslides":
          setAttributes({
            maxslides: parseInt(val)
          });
          break;

        case "moveslides":
          setAttributes({
            moveslides: parseInt(val)
          });
          break;

        case "slidewidth":
          setAttributes({
            slidewidth: parseInt(val)
          });
          break;

        case "slidemargin":
          setAttributes({
            slidemargin: parseInt(val)
          });
          break;

        case "slidealign":
          setAttributes({
            slidealign: val
          });
          break;

        case "slidedesclength":
          setAttributes({
            slidedesclength: parseInt(val)
          });
          break;

        case "slideimageheight":
          setAttributes({
            slideimageheight: parseInt(val)
          });
          break;
      }

      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" + attributes.blockid + "-minslides",
        attributes.minslides
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" + attributes.blockid + "-maxslides",
        attributes.maxslides
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" + attributes.blockid + "-moveslides",
        attributes.moveslides
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidewidth",
        attributes.slidewidth
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidemargin",
        attributes.slidemargin
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidealign",
        attributes.slidealign
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" +
          attributes.blockid +
          "-slidedesclength",
        attributes.slidedesclength
      );
      localStorage.setItem(
        "lpInspectorFeatSliderSetting-" +
          attributes.blockid +
          "-slideimageheight",
        attributes.slideimageheight
      );
      curriculumfeatslider_reset(attributes.blockid, 750);
    }

    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-minslides",
      attributes.minslides
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-maxslides",
      attributes.maxslides
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-moveslides",
      attributes.moveslides
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidewidth",
      attributes.slidewidth
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidemargin",
      attributes.slidemargin
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidealign",
      attributes.slidealign
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" + attributes.blockid + "-slidedesclength",
      attributes.slidedesclength
    );
    localStorage.setItem(
      "lpInspectorFeatSliderSetting-" +
        attributes.blockid +
        "-slideimageheight",
      attributes.slideimageheight
    );

    function onTitleChange(elem, index) {
      var blktitle = elem.target.value;
      blktitle = blktitle == "" ? "" : blktitle;
      var blkid = elem.target.getAttribute("blk");
      cgbGlobal["curriculum_feat_title_" + blkid] = blktitle;
      setAttributes({
        blocktitle: blktitle
      });
    }

    function onSearch(elem, index) {
      var searchstring = elem.target.value.toLowerCase();
      setAttributes({
        resourcesubjectfilter: ""
      });
      setAttributes({
        curriculumsubjectfilter: ""
      });
      setAttributes({
        searchstring: searchstring
      });
    }

    function onResourceFilterSubject(elem, index) {
      var val = elem.target.value;
      setAttributes({
        searchstring: ""
      });

      if (val !== "") {
        //console.log('SEARCHSTRING:'+val);
        setAttributes({
          resourcesubjectfilter: val
        });
      } else {
        //console.log('SEARCHSTRING Blnk:'+val);
        setAttributes({
          resourcesubjectfilter: ""
        });
      }
    }

    function onCurriculumFilterSubject(elem, index) {
      var val = elem.target.value;
      setAttributes({
        searchstring: ""
      });

      if (val !== "") {
        //console.log('SEARCHSTRING:'+val);
        setAttributes({
          curriculumsubjectfilter: val
        });
      } else {
        //console.log('SEARCHSTRING Blnk:'+val);
        setAttributes({
          curriculumsubjectfilter: ""
        });
      }
    }

    function onFilterSearchToggle(elem, index) {
      //console.log('FILTER TOGGLE: '+attributes.filtertype)
      setAttributes({
        resourcesubjectfilter: ""
      });
      setAttributes({
        curriculumsubjectfilter: ""
      });
      setAttributes({
        searchstring: ""
      });

      if (attributes.filtertype == "search") {
        setAttributes({
          filtertype: "subject"
        });
      } else {
        setAttributes({
          filtertype: "search"
        });
      }
    }

    function onBlockWidthChange(elem, index) {
      var val = elem.target.value;
      setAttributes({
        blockwidth: val
      });
      jQuery("#block-" + attributes.blockid).css({
        maxWidth: val + "px"
      });
      localStorage.setItem(
        "lpInspectorFeatBlockwidth-" + attributes.blockid,
        attributes.blockwidth
      );
    }

    jQuery("#block-" + attributes.blockid).css({
      maxWidth: attributes.blockwidth + "px"
    });
    localStorage.setItem(
      "lpInspectorFeatBlockwidth-" + attributes.blockid,
      attributes.blockwidth
    ); 
    
    jQuery(document).on('click','.oer_curriculum_right_featuredwpr', function(e){
      var curblkid = jQuery(e.target).closest('.block-editor-block-list__block').attr('data-block');
      wp.data.dispatch( 'core/block-editor' ).selectBlock(curblkid);
    });
    
    //console.log(attributes.blockid);
    //console.log(highlighted);
    //console.log(attributes.selectedfeatured);
    //console.log(attributes.blockid);
    //console.log(feats);
    //console.log(res_subj_arr);
    //console.log(cur_subj_arr);
    //console.log(attributes.minslides);
    //console.log(attributes.maxslides);
    //console.log(attributes.moveslides);
    //console.log(attributes.slidewidth);
    //console.log(attributes.slidemargin);
    //const highlightDropdownOptions = ['resources','curriculum'];
    //const dropClass = (attributes.highlight == 'resources')? 'button oer_curriculum_inspector_feat_addResources': 'button oer_curriculum_inspector_feat_addCurriculum';
    //const dropText = (attributes.highlight == 'resources')? 'Resources': 'Curriculum';

    let looper = [1];
    return /*#__PURE__*/ React.createElement(
      "div",
      null,
      /*#__PURE__*/ React.createElement(
        InspectorControls,
        null,
        /*#__PURE__*/ React.createElement(
          PanelBody,
          {
            title: __("Curriculum Featured Block settings"),
            initialOpen: true
          },
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_subject"
              },
              "Block Title:"
            ),
            /*#__PURE__*/ React.createElement("input", {
              type: "text",
              onChange: onTitleChange,
              class: "ls_inspector_feat_title",
              value: attributes.blocktitle,
              blk: attributes.blockid
            })
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_subject"
              },
              "Block Width"
            ),
            /*#__PURE__*/ React.createElement("input", {
              type: "number",
              onChange: onBlockWidthChange,
              class: "ls_inspector_feat_blockwidth",
              value: attributes.blockwidth,
              blk: attributes.blockid
            }),
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_subject"
              },
              /*#__PURE__*/ React.createElement(
                "em",
                null,
                "Note: Block width setting is only used to simulate the frontend width at backend and will not affect the frontend."
              )
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_feat_modal_resource_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oer_curriculum_inspector_feat_modal_content_main"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oer_curriculum_inspector_feat_modal_wrapper_close"
                },
                /*#__PURE__*/ React.createElement("span", {
                  class: "dashicons dashicons-no"
                })
              ),
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oer_curriculum_inspector_feat_modal_center"
                },
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "oer_curriculum_inspector_feat_modal_table"
                  },
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "oer_curriculum_inspector_feat_modal_cell"
                    },
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "oer_curriculum_inspector_feat_modal"
                      },
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_feat_search_wrapper"
                        },
                        /*#__PURE__*/ React.createElement(
                          "div",
                          {
                            class: "oer_curriculum_inspector_feat_search_header"
                          },
                          "Resources"
                        ),
                        looper.map((tmp, index) => {
                          if (attributes.filtertype == "subject") {
                            return /*#__PURE__*/ React.createElement("input", {
                              type: "button",
                              onClick: onFilterSearchToggle,
                              class: "button",
                              value: "Filter by subject"
                            });
                          } else {
                            return /*#__PURE__*/ React.createElement("input", {
                              type: "button",
                              onClick: onFilterSearchToggle,
                              class: "button",
                              value: "Filter by search"
                            });
                          }
                        }),
                        looper.map((tmp, index) => {
                          if (attributes.filtertype == "subject") {
                            return /*#__PURE__*/ React.createElement("input", {
                              type: "text",
                              onChange: onSearch,
                              fet: "res",
                              id: "oer_curriculum_inspector_feat_search",
                              class: "oer_curriculum_inspector_feat_search",
                              value: attributes.searchstring
                            });
                          } else {
                            return /*#__PURE__*/ React.createElement(
                              "select",
                              {
                                id:
                                  "oer_curriculum_inspector_feat_subject_select",
                                onChange: onResourceFilterSubject,
                                value: attributes.resourcesubjectfilter
                              },
                              /*#__PURE__*/ React.createElement(
                                "option",
                                {
                                  value: ""
                                },
                                "All"
                              ),
                              res_subj_arr.map((subject, index) => {
                                if (
                                  subject.term_id ==
                                  attributes.resourcesubjectfilter
                                ) {
                                  if (subject.parent == 0) {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        selected: "selected",
                                        value: subject.term_id,
                                        class:
                                          "oer_curriculum_inspector_feat_subject_select_bold"
                                      },
                                      subject.name + " (" + subject.cnt + ")"
                                    );
                                  } else {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        selected: "selected",
                                        value: subject.term_id
                                      },
                                      "├ " +
                                        subject.name +
                                        " (" +
                                        subject.cnt +
                                        ")"
                                    );
                                  }
                                } else {
                                  if (subject.parent == 0) {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        value: subject.term_id,
                                        class:
                                          "oer_curriculum_inspector_feat_subject_select_bold"
                                      },
                                      subject.name + " (" + subject.cnt + ")"
                                    );
                                  } else {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        value: subject.term_id
                                      },
                                      "├ " +
                                        subject.name +
                                        " (" +
                                        subject.cnt +
                                        ")"
                                    );
                                  }
                                }
                              })
                            );
                          }
                        })
                      ),
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_feat_modal_content"
                        },
                        /*#__PURE__*/ React.createElement(
                          "div",
                          {
                            class:
                              "oer_curriculum_inspector_feat_modal_content_subcontainer"
                          },
                          res_list_arr.map((resource, index) => {
                            let tex = highlighted.findIndex(
                              findMatch(resource.id)
                            );
                            let str = attributes.searchstring;
                            let flt = attributes.resourcesubjectfilter;
                            let ttl = resource.title.toLowerCase();
                            let tax = resource.tax.toString();
                            var taxarray = tax.split("|");

                            if (
                              attributes.searchstring == "" ||
                              !attributes.searchstring
                            ) {
                              //empty search string
                              if (flt != "" && flt !== undefined) {
                                if (taxarray.includes(flt)) {
                                  // Subject Matched
                                  //if( tax.indexOf(flt) !== -1 ){ // Subject Matched
                                  if (tex != -1) {
                                    // Checked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: resource.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          checked: "checked",
                                          onClick: updateHighlight,
                                          fet: "res",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: resource.id,
                                          tax: tax
                                        }
                                      ),
                                      unescape(resource.title)
                                    );
                                  } else {
                                    //Unchecked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: resource.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          onClick: updateHighlight,
                                          fet: "res",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: resource.id,
                                          tax: tax
                                        }
                                      ),
                                      unescape(resource.title)
                                    );
                                  }
                                }
                              } else {
                                if (tex != -1) {
                                  // Checked
                                  return /*#__PURE__*/ React.createElement(
                                    "label",
                                    {
                                      class:
                                        "components-base-control__label ls_inspector_feat_modal_label",
                                      srch: resource.title.toLowerCase()
                                    },
                                    /*#__PURE__*/ React.createElement("img", {
                                      src:
                                        cgbGlobal["pluginDirUrl"] +
                                        "/images/preloader.gif"
                                    }),
                                    /*#__PURE__*/ React.createElement("input", {
                                      checked: "checked",
                                      onClick: updateHighlight,
                                      fet: "res",
                                      id: "inspector-checkbox-control-" + index,
                                      idx: index,
                                      class: "ls_inspector_feat_modal_checkbox",
                                      type: "checkbox",
                                      data: resource.id,
                                      tax: tax
                                    }),
                                    unescape(resource.title)
                                  );
                                } else {
                                  //Unchecked
                                  return /*#__PURE__*/ React.createElement(
                                    "label",
                                    {
                                      class:
                                        "components-base-control__label ls_inspector_feat_modal_label",
                                      srch: resource.title.toLowerCase()
                                    },
                                    /*#__PURE__*/ React.createElement("img", {
                                      src:
                                        cgbGlobal["pluginDirUrl"] +
                                        "/images/preloader.gif"
                                    }),
                                    /*#__PURE__*/ React.createElement("input", {
                                      onClick: updateHighlight,
                                      fet: "res",
                                      id: "inspector-checkbox-control-" + index,
                                      idx: index,
                                      class: "ls_inspector_feat_modal_checkbox",
                                      type: "checkbox",
                                      data: resource.id,
                                      tax: tax
                                    }),
                                    unescape(resource.title)
                                  );
                                }
                              }
                            } else {
                              //search string is provided
                              if (taxarray.includes(flt)) {
                                // Subject Matched
                                //if(tax.indexOf(flt) !== -1){ // subject filter matched
                                if (ttl.indexOf(str) !== -1) {
                                  // search string matched
                                  if (tex != -1) {
                                    // Checked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: resource.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          checked: "checked",
                                          onClick: updateHighlight,
                                          fet: "res",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: resource.id,
                                          tax: tax
                                        }
                                      ),
                                      unescape(resource.title)
                                    );
                                  } else {
                                    return (
                                      /*#__PURE__*/
                                      // Unchecked
                                      React.createElement(
                                        "label",
                                        {
                                          class:
                                            "components-base-control__label ls_inspector_feat_modal_label",
                                          srch: resource.title.toLowerCase()
                                        },
                                        /*#__PURE__*/ React.createElement(
                                          "img",
                                          {
                                            src:
                                              cgbGlobal["pluginDirUrl"] +
                                              "/images/preloader.gif"
                                          }
                                        ),
                                        /*#__PURE__*/ React.createElement(
                                          "input",
                                          {
                                            onClick: updateHighlight,
                                            fet: "res",
                                            id:
                                              "inspector-checkbox-control-" +
                                              index,
                                            idx: index,
                                            class:
                                              "ls_inspector_feat_modal_checkbox",
                                            type: "checkbox",
                                            data: resource.id,
                                            tax: tax
                                          }
                                        ),
                                        unescape(resource.title)
                                      )
                                    );
                                  }
                                }
                              } else {
                                //subject filter mismatch
                                if (ttl.indexOf(str) !== -1) {
                                  // search string matched
                                  if (tex != -1) {
                                    // Checked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: resource.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          checked: "checked",
                                          onClick: updateHighlight,
                                          fet: "res",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: resource.id,
                                          tax: tax
                                        }
                                      ),
                                      unescape(resource.title)
                                    );
                                  } else {
                                    return (
                                      /*#__PURE__*/
                                      // Unchecked
                                      React.createElement(
                                        "label",
                                        {
                                          class:
                                            "components-base-control__label ls_inspector_feat_modal_label",
                                          srch: resource.title.toLowerCase()
                                        },
                                        /*#__PURE__*/ React.createElement(
                                          "img",
                                          {
                                            src:
                                              cgbGlobal["pluginDirUrl"] +
                                              "/images/preloader.gif"
                                          }
                                        ),
                                        /*#__PURE__*/ React.createElement(
                                          "input",
                                          {
                                            onClick: updateHighlight,
                                            fet: "res",
                                            id:
                                              "inspector-checkbox-control-" +
                                              index,
                                            idx: index,
                                            class:
                                              "ls_inspector_feat_modal_checkbox",
                                            type: "checkbox",
                                            data: resource.id,
                                            tax: tax
                                          }
                                        ),
                                        unescape(resource.title)
                                      )
                                    );
                                  }
                                }
                              }
                            }
                          })
                        )
                      ),
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_feat_search_footer"
                        },
                        /*#__PURE__*/ React.createElement("input", {
                          type: "button",
                          class:
                            "button oer_curriculum_inspector_feat_quickswitchbutton",
                          onClick: onModalQuickButton,
                          typ: "res",
                          value: "Curriculum lists >"
                        })
                      )
                    )
                  )
                )
              )
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_feat_modal_curriculum_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oer_curriculum_inspector_feat_modal_content_main"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oer_curriculum_inspector_feat_modal_wrapper_close"
                },
                /*#__PURE__*/ React.createElement("span", {
                  class: "dashicons dashicons-no"
                })
              ),
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oer_curriculum_inspector_feat_modal_center"
                },
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "oer_curriculum_inspector_feat_modal_table"
                  },
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "oer_curriculum_inspector_feat_modal_cell"
                    },
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "oer_curriculum_inspector_feat_modal"
                      },
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_feat_search_wrapper"
                        },
                        /*#__PURE__*/ React.createElement(
                          "div",
                          {
                            class: "oer_curriculum_inspector_feat_search_header"
                          },
                          "Curriculum"
                        ),
                        looper.map((tmp, index) => {
                          if (attributes.filtertype == "subject") {
                            return /*#__PURE__*/ React.createElement("input", {
                              type: "button",
                              onClick: onFilterSearchToggle,
                              class: "button",
                              value: "Filter by subject"
                            });
                          } else {
                            return /*#__PURE__*/ React.createElement("input", {
                              type: "button",
                              onClick: onFilterSearchToggle,
                              class: "button",
                              value: "Filter by search"
                            });
                          }
                        }),
                        looper.map((tmp, index) => {
                          if (attributes.filtertype == "subject") {
                            return /*#__PURE__*/ React.createElement("input", {
                              type: "text",
                              onChange: onSearch,
                              fet: "res",
                              id: "oer_curriculum_inspector_feat_search",
                              class: "oer_curriculum_inspector_feat_search",
                              value: attributes.searchstring
                            });
                          } else {
                            return /*#__PURE__*/ React.createElement(
                              "select",
                              {
                                id:
                                  "oer_curriculum_inspector_feat_subject_select",
                                onChange: onCurriculumFilterSubject,
                                value: attributes.curriculumsubjectfilter
                              },
                              /*#__PURE__*/ React.createElement(
                                "option",
                                {
                                  value: ""
                                },
                                "All"
                              ),
                              cur_subj_arr.map((subject, index) => {
                                if (
                                  subject.term_id ==
                                  attributes.curriculumsubjectfilter
                                ) {
                                  if (subject.parent == 0) {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        selected: "selected",
                                        value: subject.term_id,
                                        class:
                                          "oer_curriculum_inspector_feat_subject_select_bold"
                                      },
                                      subject.name + " (" + subject.cnt + ")"
                                    );
                                  } else {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        selected: "selected",
                                        value: subject.term_id
                                      },
                                      "├ " +
                                        subject.name +
                                        " (" +
                                        subject.cnt +
                                        ")"
                                    );
                                  }
                                } else {
                                  if (subject.parent == 0) {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        value: subject.term_id,
                                        class:
                                          "oer_curriculum_inspector_feat_subject_select_bold"
                                      },
                                      subject.name + " (" + subject.cnt + ")"
                                    );
                                  } else {
                                    return /*#__PURE__*/ React.createElement(
                                      "option",
                                      {
                                        value: subject.term_id
                                      },
                                      "├ " +
                                        subject.name +
                                        " (" +
                                        subject.cnt +
                                        ")"
                                    );
                                  }
                                }
                              })
                            );
                          }
                        })
                      ),
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_feat_modal_content"
                        },
                        /*#__PURE__*/ React.createElement(
                          "div",
                          {
                            class:
                              "oer_curriculum_inspector_feat_modal_content_subcontainer"
                          },
                          cur_list_arr.map((curriculum, index) => {
                            let tex = highlighted.findIndex(
                              findMatch(curriculum.id)
                            );
                            let str = attributes.searchstring;
                            let flt = attributes.curriculumsubjectfilter;
                            let ttl = curriculum.title.toLowerCase();
                            let tax = curriculum.tax.toString();

                            if (
                              attributes.searchstring == "" ||
                              !attributes.searchstring
                            ) {
                              //empty search string
                              if (flt != "" && flt !== undefined) {
                                if (tax.indexOf(flt) !== -1) {
                                  // Subject Matched
                                  if (tex != -1) {
                                    // Checked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: curriculum.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          checked: "checked",
                                          onClick: updateHighlight,
                                          fet: "cur",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: curriculum.id,
                                          tax: tax
                                        }
                                      ),
                                      curriculum.title
                                    );
                                  } else {
                                    //Unchecked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: curriculum.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          onClick: updateHighlight,
                                          fet: "cur",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: curriculum.id,
                                          tax: tax
                                        }
                                      ),
                                      curriculum.title
                                    );
                                  }
                                }
                              } else {
                                if (tex != -1) {
                                  // Checked
                                  return /*#__PURE__*/ React.createElement(
                                    "label",
                                    {
                                      class:
                                        "components-base-control__label ls_inspector_feat_modal_label",
                                      srch: curriculum.title.toLowerCase()
                                    },
                                    /*#__PURE__*/ React.createElement("img", {
                                      src:
                                        cgbGlobal["pluginDirUrl"] +
                                        "/images/preloader.gif"
                                    }),
                                    /*#__PURE__*/ React.createElement("input", {
                                      checked: "checked",
                                      onClick: updateHighlight,
                                      fet: "cur",
                                      id: "inspector-checkbox-control-" + index,
                                      idx: index,
                                      class: "ls_inspector_feat_modal_checkbox",
                                      type: "checkbox",
                                      data: curriculum.id,
                                      tax: tax
                                    }),
                                    curriculum.title
                                  );
                                } else {
                                  //Unchecked
                                  return /*#__PURE__*/ React.createElement(
                                    "label",
                                    {
                                      class:
                                        "components-base-control__label ls_inspector_feat_modal_label",
                                      srch: curriculum.title.toLowerCase()
                                    },
                                    /*#__PURE__*/ React.createElement("img", {
                                      src:
                                        cgbGlobal["pluginDirUrl"] +
                                        "/images/preloader.gif"
                                    }),
                                    /*#__PURE__*/ React.createElement("input", {
                                      onClick: updateHighlight,
                                      fet: "cur",
                                      id: "inspector-checkbox-control-" + index,
                                      idx: index,
                                      class: "ls_inspector_feat_modal_checkbox",
                                      type: "checkbox",
                                      data: curriculum.id,
                                      tax: tax
                                    }),
                                    curriculum.title
                                  );
                                }
                              }
                            } else {
                              //search string is provided
                              if (tax.indexOf(flt) !== -1) {
                                // subject filter matched
                                if (ttl.indexOf(str) !== -1) {
                                  // search string matched
                                  if (tex != -1) {
                                    // Checked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: curriculum.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          checked: "checked",
                                          onClick: updateHighlight,
                                          fet: "cur",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: curriculum.id,
                                          tax: tax
                                        }
                                      ),
                                      curriculum.title
                                    );
                                  } else {
                                    return (
                                      /*#__PURE__*/
                                      // Unchecked
                                      React.createElement(
                                        "label",
                                        {
                                          class:
                                            "components-base-control__label ls_inspector_feat_modal_label",
                                          srch: curriculum.title.toLowerCase()
                                        },
                                        /*#__PURE__*/ React.createElement(
                                          "img",
                                          {
                                            src:
                                              cgbGlobal["pluginDirUrl"] +
                                              "/images/preloader.gif"
                                          }
                                        ),
                                        /*#__PURE__*/ React.createElement(
                                          "input",
                                          {
                                            onClick: updateHighlight,
                                            fet: "cur",
                                            id:
                                              "inspector-checkbox-control-" +
                                              index,
                                            idx: index,
                                            class:
                                              "ls_inspector_feat_modal_checkbox",
                                            type: "checkbox",
                                            data: curriculum.id,
                                            tax: tax
                                          }
                                        ),
                                        curriculum.title
                                      )
                                    );
                                  }
                                }
                              } else {
                                //subject filter mismatch
                                if (ttl.indexOf(str) !== -1) {
                                  // search string matched
                                  if (tex != -1) {
                                    // Checked
                                    return /*#__PURE__*/ React.createElement(
                                      "label",
                                      {
                                        class:
                                          "components-base-control__label ls_inspector_feat_modal_label",
                                        srch: curriculum.title.toLowerCase()
                                      },
                                      /*#__PURE__*/ React.createElement("img", {
                                        src:
                                          cgbGlobal["pluginDirUrl"] +
                                          "/images/preloader.gif"
                                      }),
                                      /*#__PURE__*/ React.createElement(
                                        "input",
                                        {
                                          checked: "checked",
                                          onClick: updateHighlight,
                                          fet: "cur",
                                          id:
                                            "inspector-checkbox-control-" +
                                            index,
                                          idx: index,
                                          class:
                                            "ls_inspector_feat_modal_checkbox",
                                          type: "checkbox",
                                          data: curriculum.id,
                                          tax: tax
                                        }
                                      ),
                                      curriculum.title
                                    );
                                  } else {
                                    return (
                                      /*#__PURE__*/
                                      // Unchecked
                                      React.createElement(
                                        "label",
                                        {
                                          class:
                                            "components-base-control__label ls_inspector_feat_modal_label",
                                          srch: curriculum.title.toLowerCase()
                                        },
                                        /*#__PURE__*/ React.createElement(
                                          "img",
                                          {
                                            src:
                                              cgbGlobal["pluginDirUrl"] +
                                              "/images/preloader.gif"
                                          }
                                        ),
                                        /*#__PURE__*/ React.createElement(
                                          "input",
                                          {
                                            onClick: updateHighlight,
                                            fet: "cur",
                                            id:
                                              "inspector-checkbox-control-" +
                                              index,
                                            idx: index,
                                            class:
                                              "ls_inspector_feat_modal_checkbox",
                                            type: "checkbox",
                                            data: curriculum.id,
                                            tax: tax
                                          }
                                        ),
                                        curriculum.title
                                      )
                                    );
                                  }
                                }
                              }
                            }
                          })
                        )
                      ),
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_feat_search_footer"
                        },
                        /*#__PURE__*/ React.createElement("input", {
                          type: "button",
                          class:
                            "button oer_curriculum_inspector_feat_quickswitchbutton",
                          onClick: onModalQuickButton,
                          typ: "cur",
                          value: "Resources lists >"
                        })
                      )
                    )
                  )
                )
              )
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_subject"
              },
              "Featured List:"
            ),
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oer_curriculum_inspector_feat_addbutton_wrapper"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "button oer_curriculum_inspector_feat_addResources"
                },
                "Add Resources"
              ),
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "button oer_curriculum_inspector_feat_addCurriculum"
                },
                "Add Curriculum"
              )
            ),
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oer_curriculum_inspector_feat_hlite_list"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  id: "oer_curriculum_inspector_feat_hlite_featured",
                  class: "oer_curriculum_inspector_feat_hlite_featured"
                },
                feats.map((feat, index) => {
                  if (prevelem == "li") {
                    return /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        draggable: true,
                        onMouseup: updateposition,
                        class:
                          "oer_curriculum_inspector_feat_hlite_node stay " +
                          feat[6],
                        data: feat[0],
                        typ: feat[6]
                      },
                      feat[1],
                      /*#__PURE__*/ React.createElement("span", {
                        class: "dashicons dashicons-dismiss"
                      })
                    );
                  } else {
                    return /*#__PURE__*/ React.createElement(
                      "li",
                      {
                        draggable: true,
                        onMouseup: updateposition,
                        class:
                          "oer_curriculum_inspector_feat_hlite_node stay " +
                          feat[6],
                        data: feat[0],
                        typ: feat[6]
                      },
                      feat[1],
                      /*#__PURE__*/ React.createElement("span", {
                        class: "dashicons dashicons-dismiss"
                      })
                    );
                  }
                })
              ),
              /*#__PURE__*/ React.createElement("div", {
                class:
                  "button oer_curriculum_inspector_feat_hlite_reposition_trigger",
                onClick: updateposition,
                blkid: attributes.blockid
              }),
              /*#__PURE__*/ React.createElement("div", {
                class:
                  "button oer_curriculum_inspector_feat_hlite_remove_trigger",
                height: "0",
                width: "0",
                onClick: removefeatured,
                blkid: attributes.blockid
              })
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_subject"
              },
              "Slider Setting:"
            ),
            /*#__PURE__*/ React.createElement(
              "table",
              {
                class: "oer_curriculum_inspector_feat_slider_setting",
                cellspacing: "2"
              },
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "The minimum number of slides to be shown. Slides will be sized down if slider becomes smaller than the original size."
                    )
                  ),
                  "Min. Slides:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "select",
                    {
                      id: "oer_curriculum_inspector_feat_slider_minslides",
                      onChange: onSettingChange,
                      typ: "minslides",
                      value: attributes.minslides
                    },
                    globalSettingOptions.map((incr, index) => {
                      let ret =
                        incr == attributes.minslides
                          ? /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                selected: true,
                                value: incr
                              },
                              incr
                            )
                          : /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                value: incr
                              },
                              incr
                            );
                      return ret;
                    })
                  )
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "The maximum number of slides to be shown. Slides will be sized up if slider becomes larger than the original size."
                    )
                  ),
                  "Max. Slides:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "select",
                    {
                      id: "oer_curriculum_inspector_feat_slider_maxslides",
                      onChange: onSettingChange,
                      typ: "maxslides",
                      value: attributes.maxslides
                    },
                    globalSettingOptions.map((incr, index) => {
                      let ret =
                        incr == attributes.maxslides
                          ? /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                selected: true,
                                value: incr
                              },
                              incr
                            )
                          : /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                value: incr
                              },
                              incr
                            );
                      return ret;
                    })
                  )
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "The number of slides to move on transition. This value must be greater than or equal to minSlides, and less than or equal to maxSlides. If value is greater than the fully-visible slides, then the count of fully-visible slides will be used."
                    )
                  ),
                  "Move Slides:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "select",
                    {
                      id: "oer_curriculum_inspector_feat_slider_moveslides",
                      onChange: onSettingChange,
                      typ: "moveslides",
                      value: attributes.moveslides
                    },
                    globalSettingOptions.map((incr, index) => {
                      let ret =
                        incr == attributes.moveslides
                          ? /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                selected: true,
                                value: incr
                              },
                              incr
                            )
                          : /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                value: incr
                              },
                              incr
                            );
                      return ret;
                    })
                  )
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "Width of each slide."
                    )
                  ),
                  "Slide Width:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement("input", {
                    type: "number",
                    id: "oer_curriculum_inspector_feat_slider_slidewidth",
                    typ: "slidewidth",
                    onChange: onSettingChange,
                    value: attributes.slidewidth
                  })
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "Space between slides"
                    )
                  ),
                  "Slide Margin:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "select",
                    {
                      id: "oer_curriculum_inspector_feat_slider_slidemargin",
                      onChange: onSettingChange,
                      typ: "slidemargin",
                      value: attributes.slidemargin
                    },
                    globalSettingMargin.map((incr, index) => {
                      let ret =
                        incr == attributes.slidemargin
                          ? /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                selected: true,
                                value: incr
                              },
                              incr
                            )
                          : /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                value: incr
                              },
                              incr
                            );
                      return ret;
                    })
                  )
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "Left, right or middle alignment"
                    )
                  ),
                  "Alignmnet:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "select",
                    {
                      id: "oer_curriculum_inspector_feat_slider_slidealign",
                      onChange: onSettingChange,
                      typ: "slidealign",
                      value: attributes.slidealign
                    },
                    globalSettingAlign.map((incr, index) => {
                      let ret =
                        incr == attributes.slidemargin
                          ? /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                selected: true,
                                value: incr
                              },
                              incr
                            )
                          : /*#__PURE__*/ React.createElement(
                              "option",
                              {
                                value: incr
                              },
                              incr
                            );
                      return ret;
                    })
                  )
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "Length of description to display."
                    )
                  ),
                  "Description length:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement("input", {
                    type: "number",
                    id: "oer_curriculum_inspector_feat_slider_slidedesclength",
                    typ: "slidedesclength",
                    onChange: onSettingChange,
                    value: attributes.slidedesclength
                  })
                )
              ),
              /*#__PURE__*/ React.createElement(
                "tr",
                null,
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement(
                    "span",
                    {
                      class: "dashicons dashicons-info tooltipped"
                    },
                    /*#__PURE__*/ React.createElement(
                      "span",
                      {
                        class: "tooltiptext"
                      },
                      "Adjust image height"
                    )
                  ),
                  "Image height:"
                ),
                /*#__PURE__*/ React.createElement(
                  "td",
                  null,
                  /*#__PURE__*/ React.createElement("input", {
                    type: "number",
                    id: "oer_curriculum_inspector_feat_slider_slideimageheight",
                    typ: "slideimageheight",
                    onChange: onSettingChange,
                    value: attributes.slideimageheight
                  })
                )
              )
            )
          ),
          /*#__PURE__*/ React.createElement("img", {
            // onload hack fires when block is added
            className: "onload-hack-pp",
            height: "0",
            width: "0",
            onLoad: onInspectorLoad,
            src: cgbGlobal["pluginDirUrl"] + "//images/default-img.jpg"
          })
        )
      ),
      /*#__PURE__*/ React.createElement(
        "div",
        {
          class: "oer_curriculum_right_featuredwpr"
        },
        /*#__PURE__*/ React.createElement(
          "div",
          {
            class:
              "oercurr-ftrdttl curriculum-feat-title_" +
              attributes.blockid
          },
          attributes.blocktitle
        ),
        /*#__PURE__*/ React.createElement(
          "ul",
          {
            class:
              "featuredwpr_bxslider featuredwpr_bxslider_" + attributes.blockid,
            blk: attributes.blockid
          },
          feats.map((feat, index) => {
            //let ctnt = feat[2].replace(/<[^>]+>/g, '');
            let ctnt = feat[2];

            if (ctnt.length > attributes.slidedesclength) {
              ctnt =
                unescape(ctnt.substr(0, attributes.slidedesclength)) + "...";
            } //console.log(feat);

            return /*#__PURE__*/ React.createElement(
              "li",
              {
                atrr: feat[0]
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "frtdsnglwpr"
                },
                /*#__PURE__*/ React.createElement(
                  "a",
                  {
                    href: feat[3],
                    tabindex: "-1"
                  },
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "img"
                    },
                    /*#__PURE__*/ React.createElement("img", {
                      src: feat[4],
                      alt: feat[1]
                    })
                  )
                ),
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "ttl"
                  },
                  /*#__PURE__*/ React.createElement(
                    "a",
                    {
                      href: feat[3],
                      tabindex: "-1"
                    },
                    feat[1]
                  )
                ),
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "desc"
                  },
                  ctnt
                )
              )
            );
          })
        )
      )
    );
  },

  /**
   * The save function defines the way in which the different attributes should be combined
   * into the final markup, which is then serialized by Gutenberg into post_content.
   *
   * The "save" property must be specified and must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   *
   * @param {Object} props Props.
   * @returns {Mixed} JSX Frontend HTML.
   */
  save: (props) => {
    return null;
  }
});
