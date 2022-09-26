!function(){"use strict";var e,t={101:function(){var e=window.wp.blocks,t=window.wp.element,r=window.wp.i18n,n=window.wp.blockEditor,l=window.wp.components,a=(window.wp.data,[]);function i(e){const i=e.attributes,c=e.setAttributes;let u=window.oercurrBlocksJson?(0,n.useBlockProps)():"";var o=new wp.api.collections.Inquiryset;0==a.length&&o.fetch().then((function(e){e.length>0?(c({status:""}),a=[],e.map(((e,t)=>{a.push({id:e.id,link:"",title:e.title.rendered,img:""})}))):c({status:"No curriculum found. Please create some first"})}));return(0,t.createElement)("div",u,(0,t.createElement)(n.InspectorControls,null,(0,t.createElement)(l.PanelBody,{title:oercurr__t("Curriculum Thumbnail Block settings"),initialOpen:!0},i.status>""?(0,t.createElement)("p",{class:"oercurr_thumbanail_block_warning"},i.status):(0,t.createElement)("select",{id:"oercurr_thumbnail_block_curriculum_select",onChange:e=>{let t=e.target.value;wp.apiFetch({url:curr_ctb_block.home_url+"/wp-json/oercurr/thumbnail/getcurriculum?cid="+e.target.value}).then((e=>{let r=JSON.parse(e);c({selectedInquirySet:t,title:r.name,link:r.link,featuredImage:r.img,grade:r.grade})}))},value:i.selectedInquirySet},(0,t.createElement)("option",{selected:0==i.selectedInquirySet?"selected":"",idx:"-1",value:"0"},oercurr__t("Select Curriculum")),a.map(((e,r)=>i.selectedInquirySet==e.id?(0,t.createElement)("option",{selected:!0,value:e.id,idx:r},e.title):(0,t.createElement)("option",{value:e.id,idx:r},e.title)))),(0,t.createElement)(l.ToggleControl,{label:(0,r.__)("Open In New Window/Tab"),help:i.openInNewTab?(0,r.__)("Open link in new window/tab","five"):(0,r.__)("Open link in thesame window/tab","five"),checked:!!i.openInNewTab,onChange:e=>{return t=!i.openInNewTab,void c({openInNewTab:t});var t}}))),(0,t.createElement)("div",{class:"oercurr-thumbnail-block-padding"},(0,t.createElement)("div",{class:"media-image"},(0,t.createElement)("div",{class:"image-thumbnail"},(0,t.createElement)("div",{class:"image-section"},"default"!=i.featuredImage?(0,t.createElement)("a",{href:i.link,target:i.openInNewTab?"_blank":"_self",rel:"noopener noreferrer"},(0,t.createElement)("img",{src:i.featuredImage,alt:"",class:"img-thumbnail-square img-responsive img-loaded"})):(0,t.createElement)("h4",null,"Please select a curriculum")))),(()=>{if(""==i.status)return(0,t.createElement)("div",{class:void 0===i.grade||""===i.grade.trim()?"oercurr-thumbnail-block-grades hide":"oercurr-thumbnail-block-grades"},(0,t.createElement)("span",null,i.grade))})(),(0,t.createElement)("div",{class:"custom-bg-dark oercurr-thumbnail-block-bg-dark"}),(0,t.createElement)("div",{class:"oercurr-thumbnail-block-description"},(0,t.createElement)("h4",null,i.title))))}function c(e){const r=e.attributes;return e.setAttributes,(0,t.createElement)("div",null,(0,t.createElement)("div",{class:"oercurr-thumbnail-block-padding"},(0,t.createElement)("div",{class:"media-image"},(0,t.createElement)("div",{class:"image-thumbnail"},(0,t.createElement)("div",{class:"image-section"},(0,t.createElement)("a",{href:r.link,target:r.openInNewTab?"_blank":"_self",rel:"noopener noreferrer"},(0,t.createElement)("img",{src:r.featuredImage,alt:"",class:"img-thumbnail-square img-responsive img-loaded"}))))),(()=>{if(""==r.status)return(0,t.createElement)("div",{class:void 0===r.grade||""===r.grade.trim()?"oercurr-thumbnail-block-grades hide":"oercurr-thumbnail-block-grades"},(0,t.createElement)("span",null,r.grade))})(),(0,t.createElement)("div",{class:"custom-bg-dark oercurr-thumbnail-block-bg-dark"}),(0,t.createElement)("div",{class:"oercurr-thumbnail-block-description"},(0,t.createElement)("h4",null,r.title))))}const{__:__}=wp.i18n;window.oercurrBlocksJson="undefined"==typeof oercurr_ctb_legacy_marker,window.oercurrBlocksJson?(0,e.registerBlockType)("oer-curriculum/oer-curriculum-thumbnail-block",{edit:i,save:c,example:()=>{}}):(0,e.registerBlockType)("oer-curriculum/oer-curriculum-thumbnail-block",{title:"Curriculum Thumbnail Block",icon:"welcome-learn-more",description:"Use this block to add OER curriculum thumbnail",category:"oer-block-category",keywords:[__("curriculum"),__("thumbnail"),__("block")],attributes:{selectedInquirySet:{type:"intiger",default:0},title:{type:"string"},link:{type:"string"},grade:{type:"string"},featuredImage:{type:"string",default:"default"},option:{type:"array"},status:{type:"string",default:""},openInNewTab:{type:"boolean",default:!1}},edit:i,save:c,example:()=>{}})}},r={};function n(e){var l=r[e];if(void 0!==l)return l.exports;var a=r[e]={exports:{}};return t[e](a,a.exports,n),a.exports}n.m=t,e=[],n.O=function(t,r,l,a){if(!r){var i=1/0;for(s=0;s<e.length;s++){r=e[s][0],l=e[s][1],a=e[s][2];for(var c=!0,u=0;u<r.length;u++)(!1&a||i>=a)&&Object.keys(n.O).every((function(e){return n.O[e](r[u])}))?r.splice(u--,1):(c=!1,a<i&&(i=a));if(c){e.splice(s--,1);var o=l();void 0!==o&&(t=o)}}return t}a=a||0;for(var s=e.length;s>0&&e[s-1][2]>a;s--)e[s]=e[s-1];e[s]=[r,l,a]},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){var e={826:0,46:0};n.O.j=function(t){return 0===e[t]};var t=function(t,r){var l,a,i=r[0],c=r[1],u=r[2],o=0;if(i.some((function(t){return 0!==e[t]}))){for(l in c)n.o(c,l)&&(n.m[l]=c[l]);if(u)var s=u(n)}for(t&&t(r);o<i.length;o++)a=i[o],n.o(e,a)&&e[a]&&e[a][0](),e[i[o]]=0;return n.O(s)},r=self.webpackChunkoer_curriculum_thumbnail_block=self.webpackChunkoer_curriculum_thumbnail_block||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))}();var l=n.O(void 0,[46],(function(){return n(101)}));l=n.O(l)}();