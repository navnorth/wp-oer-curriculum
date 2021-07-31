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
const { withSelect } = wp.data;
let selcat = [];
const parentcatlist = []; //register custom Rest Endpoint as Entity

var dispatch = wp.data.dispatch;
dispatch("core").addEntities([
  {
    name: "taxquery",
    // route name
    kind: "curriculum/v2",
    // namespace
    baseURL: "/curriculum/v2/taxquery" // API path without /wp-json
  }
]);
registerBlockType("oer-curriculum/block-curriculum-block", {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __("Curriculum Block"),
  // Block title.
  icon: "welcome-learn-more",
  // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  description: __(
    "Use this block to add a list of curriculum based on subject"
  ),
  category: "oer-block-category",
  // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [
    __("curriculum-block"),
    __("CGB Example"),
    __("create-guten-block")
  ],
  attributes: {
    blockid: {
      type: "string"
    },
    curriculums: {
      type: "array"
    },
    curriculumlength: {
      type: "integer"
    },
    categories: {
      type: "object"
    },
    selectedCategory: {
      type: "string"
    },
    displayOption: {
      type: "array",
      default: [2, 5, 10, 15, 20, 25, 30]
    },
    selper: {
      type: "string",
      default: "5"
    },
    selsrt: {
      type: "string",
      default: "modified"
    },
    sortOption: {
      type: "object",
      default: [
        {
          date: "Date Added",
          modified: "Date Updated",
          title: "Title a-z"
        }
      ]
    }
  },

  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   *
   * @param {Object} props Props.
   * @returns {Mixed} JSX Component.
   */
  edit: function (props) {
    const attributes = props.attributes;
    const setAttributes = props.setAttributes;
    const prvhtm = oercurr_cb_cgb_Global["preview_url"]; //SET BLOCK INSTANCE IDS

    const blocks = wp.data.select("core/block-editor").getBlocks();
    blocks.map((val) => {
      if (val.name == "oer-curriculum/block-curriculum-block") {
        var uniq = "cb" + new Date().getTime();
        var cid = val.clientId;
        var attr = wp.data.select("core/block-editor").getBlockAttributes(cid);
        /*
        if (!attr.blockid) {
          wp.data.dispatch("core/block-editor").updateBlockAttributes(cid, {
            blockid: uniq,
            postsPerPage: 5,
            sortBy: "modified"
          });
        }
        */
      }
    }); // RETURN MESSAGE WHILE CATEGORIES AND CURRICULUMS ARE NOT YET FULLY LOADED

    let selcat = [];

    if (typeof attributes.selectedCategory != "undefined") {
      const tmp = attributes.selectedCategory.split(",");
      const ix = tmp.indexOf("");

      if (ix > -1) {
        tmp.splice(ix, 1);
      }

      tmp.map((cat) => {
        selcat.push(parseInt(cat));
      });
    }
    /* SET CATEGORIES ATTRIBUTES */

    if (!attributes.categories) {
      wp.apiFetch({
        url: "/wp-json/curriculum/v2/catquery"
      }).then((categories) => {
        setAttributes({
          categories: categories
        });
      });
    } // Display while categories attribute is still empty

    if (!attributes.categories) {
      return "Loading categories...";
    } // Display if categories attributes is not empty but contains blank record

    if (attributes.categories && attributes.categories.length === 0) {
      return "No categories found, please add some!";
    }

    let cat_arr = [];
    cat_arr = attributes.categories; //console.log(cat_arr);

    /* SET CURRICULUM ATTRIBUTES */

    if (!attributes.curriculums) {
      let ord = "";

      if (attributes.selsrt == "title") {
        ord = "asc";
      } else {
        ord = "desc";
      }

      if (selcat != "") {
        wp.apiFetch({
          url:
            "/wp-json/curriculum/v2/taxquery?perpage=" +
            attributes.selper +
            "&terms=" +
            selcat +
            "&orderby=" +
            attributes.selsrt +
            "&order=" +
            ord
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      } else {
        wp.apiFetch({
          url: "/wp-json/curriculum/v2/taxquery?terms=0"
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      }
    } // RETURN MESSAGE WHILE CATEGORIES AND CURRICULUMS ARE NOT YET FULLY LOADED

    if (!attributes.curriculums && attributes.selectedCategory != "") {
      return "Loading curriculum ...";
    }

    let cur_arr = [];
    cur_arr = attributes.curriculums;

    if (!cur_arr && cur_arr.length != 0) {
      return "Loading curriculum ....";
    }

    jQuery(document).on(
      "click",
      ".oer_curriculum_inspector_sbjt_addSubjects",
      function (e) {
        jQuery(".oer_curriculum_inspector_sbjt_modal_resource_wrapper").show(
          300
        );
      }
    );
    jQuery(document).on(
      "click",
      ".oer_curriculum_inspector_sbjt_modal_wrapper_close",
      function (e) {
        jQuery(".oer_curriculum_inspector_sbjt_modal_resource_wrapper").hide(
          300
        );
      }
    );
    jQuery(document).on("keyup", function (event) {
      var keycode = event.keyCode ? event.keyCode : event.which;
      var target = jQuery(
        ".oer_curriculum_inspector_wrapper .oer_curriculum_inspector_sbjt_modal_resource_wrapper"
      );

      if (target.is(":visible") && keycode == 27) {
        // pressed escape key while model is visible
        target.hide(300);
      }
    });

    function onChangeCheckboxField(newValue, index) {
      if (newValue.target.checked) {
        selcat.push(newValue.target.getAttribute("data"));
      } else {
        const ex = selcat.indexOf(
          parseInt(newValue.target.getAttribute("data"))
        );

        if (ex > -1) {
          selcat.splice(ex, 1);
        }
      }

      setAttributes({
        selectedCategory: selcat.toString()
      });
      localStorage.setItem(
        "selectedCategory-" + attributes.blockid,
        selcat.toString()
      );
      let ord = attributes.selsrt == "title" ? "asc" : "desc";

      if (selcat != "") {
        wp.apiFetch({
          url:
            "/wp-json/curriculum/v2/taxquery?perpage=" +
            attributes.selper +
            "&terms=" +
            selcat +
            "&orderby=" +
            attributes.selsrt +
            "&order=" +
            ord
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      } else {
        wp.apiFetch({
          url: "/wp-json/curriculum/v2/taxquery?terms=0"
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      }
    }

    function updatePostsPerPage(e) {
      setAttributes({
        selper: e.target.value
      });
      localStorage.setItem(
        "postsPerPage-" + attributes.blockid,
        e.target.value
      );
      var tmp_selper = e.target.value;
      let ord = attributes.selsrt == "title" ? "asc" : "desc";

      if (selcat != "") {
        wp.apiFetch({
          url:
            "/wp-json/curriculum/v2/taxquery?perpage=" +
            tmp_selper +
            "&terms=" +
            selcat +
            "&orderby=" +
            attributes.selsrt +
            "&order=" +
            ord
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      } else {
        wp.apiFetch({
          url: "/wp-json/curriculum/v2/taxquery?terms=0"
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      }
    }

    function updateSortby(e) {
      /*setAttributes({sortBy: e.target.value});*/
      var tmp_selsrt = e.target.value;
      setAttributes({
        selsrt: e.target.value
      });
      localStorage.setItem("sortBy-" + attributes.selsrt, e.target.value);
      let ord = tmp_selsrt == "title" ? "asc" : "desc";

      if (selcat != "") {
        wp.apiFetch({
          url:
            "/wp-json/curriculum/v2/taxquery?perpage=" +
            attributes.selper +
            "&terms=" +
            selcat +
            "&orderby=" +
            tmp_selsrt +
            "&order=" +
            ord
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      } else {
        wp.apiFetch({
          url: "/wp-json/curriculum/v2/taxquery?terms=0"
        }).then((curriculums) => {
          setAttributes({
            curriculums: curriculums
          });
          setAttributes({
            curriculumlength: parseInt(curriculums.length)
          });
        });
      }
    }
    /*
    const displayOption = [5, 10, 15, 20, 25, 30];
    const sortOption = {
      date: "Date Added",
      modified: "Date Updated",
      title: "Title a-z"
    };
    */

    /*
    const recnt =
      typeof attributes.curriculums.length == "undefined"
        ? 0
        : attributes.curriculums.length;
    */

    console.log("AFTER:" + attributes.selsrt);
    return /*#__PURE__*/ React.createElement(
      "div",
      null,
      /*#__PURE__*/ React.createElement(
        InspectorControls,
        null,
        /*#__PURE__*/ React.createElement(
          PanelBody,
          {
            title: __("Curriculum Block settings"),
            initialOpen: true
          },
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oer_curriculum_inspector_wrapper"
            },
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oer_curriculum_inspector_sbjt_modal_resource_wrapper"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oer_curriculum_inspector_sbjt_modal_content_main"
                },
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "oer_curriculum_inspector_sbjt_modal_wrapper_close"
                  },
                  /*#__PURE__*/ React.createElement("span", {
                    class: "dashicons dashicons-no"
                  })
                ),
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "oer_curriculum_inspector_sbjt_modal_center"
                  },
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "oer_curriculum_inspector_sbjt_modal_table"
                    },
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "oer_curriculum_inspector_sbjt_modal_cell"
                      },
                      /*#__PURE__*/ React.createElement(
                        "div",
                        {
                          class: "oer_curriculum_inspector_sbjt_modal"
                        },
                        /*#__PURE__*/ React.createElement(
                          "div",
                          {
                            class: "oer_curriculum_inspector_sbjt_search_header"
                          },
                          "Subjects"
                        ),
                        /*#__PURE__*/ React.createElement(
                          "div",
                          {
                            class:
                              "oer_curriculum_inspector_sbjt_search_wrapper"
                          },
                          /*#__PURE__*/ React.createElement(
                            "div",
                            {
                              class: "oer_curriculum_inspector_subject"
                            },
                            cat_arr.map((cat, index) => {
                              return /*#__PURE__*/ React.createElement(
                                "label",
                                {
                                  class:
                                    "components-base-control__label ls_inspector_subject_label " +
                                    cat.level
                                },
                                /*#__PURE__*/ React.createElement("input", {
                                  checked:
                                    typeof selcat != "undefined" &&
                                    selcat.indexOf(cat.term_id) != -1
                                      ? "checked"
                                      : "",
                                  id: "inspector-checkbox-control-" + index,
                                  class:
                                    "ls_inspector_subject_checkbox " +
                                    cat.level,
                                  type: "checkbox",
                                  data: cat.term_id,
                                  parent: cat.parent,
                                  onClick: onChangeCheckboxField
                                }),
                                cat.name + " (" + cat.cnt + ")"
                              );
                            })
                          )
                        ),
                        /*#__PURE__*/ React.createElement("div", {
                          class: "oer_curriculum_inspector_sbjt_search_footer"
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
              "Subjects:"
            ),
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oer_curriculum_inspector_sbjt_addbutton_wrapper"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "button oer_curriculum_inspector_sbjt_addSubjects"
                },
                "Add Subjects"
              )
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class:
                "oer_curriculum_inspector_wrapper oer_curriculum_inspector_Postperpage"
            },
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_postperpage_select"
              },
              "Posts Per Page:"
            ),
            /*#__PURE__*/ React.createElement(
              "select",
              {
                id: "oer_curriculum_inspector_postperpage_select",
                onChange: updatePostsPerPage,
                value: attributes.selper
              },
              attributes.displayOption.map((incr, index) => {
                if (attributes.selper == incr) {
                  return /*#__PURE__*/ React.createElement(
                    "option",
                    {
                      selected: true,
                      value: incr
                    },
                    incr
                  );
                } else {
                  return /*#__PURE__*/ React.createElement(
                    "option",
                    {
                      value: incr
                    },
                    incr
                  );
                }
              })
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class:
                "oer_curriculum_inspector_wrapper oer_curriculum_inspector_Postperpage"
            },
            /*#__PURE__*/ React.createElement(
              "label",
              {
                class: "components-base-control__label",
                for: "oer_curriculum_inspector_postperpage_select"
              },
              "Sort By:"
            ),
            /*#__PURE__*/ React.createElement(
              "select",
              {
                id: "oer_curriculum_inspector_sortby_select",
                onChange: updateSortby,
                value: attributes.selsrt
              },
              Object.keys(attributes.sortOption[0]).map((key) => {
                if (attributes.selsrt == key) {
                  return /*#__PURE__*/ React.createElement(
                    "option",
                    {
                      value: key,
                      checked: true
                    },
                    attributes.sortOption[0][key]
                  );
                } else {
                  return /*#__PURE__*/ React.createElement(
                    "option",
                    {
                      value: key
                    },
                    attributes.sortOption[0][key]
                  );
                }
              })
            )
          )
        )
      ),
      /*#__PURE__*/ React.createElement(
        "div",
        {
          class: "oercurr-blk-main editor",
          blockid: attributes.blockid
        },
        /*#__PURE__*/ React.createElement(
          "div",
          {
            class: "oercurr-blk-topbar"
          },
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oercurr-blk-topbar-left"
            },
            /*#__PURE__*/ React.createElement(
              "span",
              null,
              "Browse All ",
              attributes.curriculumlength,
              " Curriculums"
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oercurr-blk-topbar-right"
            },
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oercurr-blk-topbar-display-box"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oercurr-blk-topbar-display-text"
                },
                /*#__PURE__*/ React.createElement(
                  "span",
                  null,
                  "Show ",
                  attributes.selper
                ),
                /*#__PURE__*/ React.createElement(
                  "a",
                  {
                    href: "#"
                  },
                  /*#__PURE__*/ React.createElement("i", {
                    class: "fa fa-th-list",
                    "aria-hidden": "true"
                  })
                )
              ),
              /*#__PURE__*/ React.createElement(
                "ul",
                {
                  class:
                    "oercurr-blk-topbar-display-option oercurr-blk-topbar-option"
                },
                attributes.displayOption.map((incr, index) => {
                  if (attributes.selper == incr) {
                    return /*#__PURE__*/ React.createElement(
                      "li",
                      {
                        class: "selected"
                      },
                      /*#__PURE__*/ React.createElement(
                        "a",
                        {
                          href: "#",
                          ret: incr
                        },
                        incr
                      )
                    );
                  } else {
                    return /*#__PURE__*/ React.createElement(
                      "li",
                      null,
                      /*#__PURE__*/ React.createElement(
                        "a",
                        {
                          href: "#",
                          ret: incr
                        },
                        incr
                      )
                    );
                  }
                })
              )
            ),
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oercurr-blk-topbar-sort-box"
              },
              /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oercurr-blk-topbar-sort-text"
                },
                /*#__PURE__*/ React.createElement(
                  "span",
                  null,
                  "Sort by: ",
                  attributes.sortOption[0][attributes.selsrt]
                ),
                /*#__PURE__*/ React.createElement(
                  "a",
                  {
                    href: "#"
                  },
                  /*#__PURE__*/ React.createElement("i", {
                    class: "fa fa-sort",
                    "aria-hidden": "true"
                  })
                )
              ),
              /*#__PURE__*/ React.createElement(
                "ul",
                {
                  class:
                    "oercurr-blk-topbar-sort-option oercurr-blk-topbar-option"
                },
                Object.keys(attributes.sortOption[0]).map((key) => {
                  if (attributes.selsrt == key) {
                    return /*#__PURE__*/ React.createElement(
                      "li",
                      {
                        class: "selected"
                      },
                      /*#__PURE__*/ React.createElement(
                        "a",
                        {
                          href: "#",
                          ret: key
                        },
                        attributes.sortOption[0][key]
                      )
                    );
                  } else {
                    return /*#__PURE__*/ React.createElement(
                      "li",
                      null,
                      /*#__PURE__*/ React.createElement(
                        "a",
                        {
                          href: "#",
                          ret: key
                        },
                        attributes.sortOption[0][key]
                      )
                    );
                  }
                })
              )
            )
          )
        ),
        /*#__PURE__*/ React.createElement(
          "div",
          {
            id: "oer_curriculum_cur_blk_content_wrapper",
            class: "oercurr-blk-wrapper"
          },
          /*#__PURE__*/ React.createElement(
            "div",
            {
              id: "oercurr-blk-content_drop"
            },
            cur_arr.map((post, index) => {
              if (attributes.curriculums.length > 0) {
                let ctnt = post.content.replace(/<[^>]+>/g, "");

                if (ctnt.length <= 180) {
                  ctnt = ctnt + "....";
                } else {
                  ctnt = ctnt.substr(1, 180) + "...";
                }

                let grd = 0;
                let grdtxt = "";

                if (post.oer_curriculum_grades != "") {
                  if (post.oer_curriculum_grades.length > 1) {
                    grdtxt = "Grades: ";
                    grd =
                      post.oer_curriculum_grades[0] +
                      "-" +
                      post.oer_curriculum_grades[
                        post.oer_curriculum_grades.length - 1
                      ];
                  } else {
                    grdtxt = "Grade: ";
                    grd = post.oer_curriculum_grades;
                  }
                } else {
                  grdtxt = "";
                  grd = "";
                }

                return /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "oercurr-blk-row"
                  },
                  /*#__PURE__*/ React.createElement(
                    "a",
                    {
                      href: post.link,
                      class: "oercurr-blk-left",
                      target: "_new",
                      rel: "noopener"
                    },
                    /*#__PURE__*/ React.createElement("img", {
                      src: post.featured_image_url,
                      alt: ""
                    })
                  ),
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "oercurr-blk-right"
                    },
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "ttl"
                      },
                      /*#__PURE__*/ React.createElement(
                        "a",
                        {
                          href: post.link,
                          target: "_new"
                        },
                        post.title
                      )
                    ),
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "oercurr-postmeta"
                      },
                      /*#__PURE__*/ React.createElement(
                        "span",
                        {
                          class: "oercurr-postmeta-grades"
                        },
                        /*#__PURE__*/ React.createElement(
                          "strong",
                          null,
                          grdtxt
                        ),
                        grd
                      )
                    ),
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "desc"
                      },
                      ctnt
                    ),
                    /*#__PURE__*/ React.createElement(
                      "div",
                      {
                        class: "oercurr-tags tagcloud"
                      },
                      post.tagsv2.map((posttags, index) => {
                        let tgar = posttags.split("|");
                        return /*#__PURE__*/ React.createElement(
                          "span",
                          null,
                          /*#__PURE__*/ React.createElement(
                            "a",
                            {
                              href:
                                oercurr_cb_cgb_Global["base_url"] +
                                "/tag/" +
                                tgar[1],
                              alt: "",
                              class: "button",
                              target: "_new"
                            },
                            tgar[0]
                          )
                        );
                      })
                    )
                  )
                );
              }
            })
          )
        )
      )
    );
  },
  save: (props) => {
    const attributes = props.attributes;
    const setAttributes = props.setAttributes;
    return /*#__PURE__*/ React.createElement(
      "div",
      {
        class: "oercurr-blk-main editor",
        blockid: attributes.blockid
      },
      /*#__PURE__*/ React.createElement(
        "div",
        {
          class: "oercurr-blk-topbar"
        },
        /*#__PURE__*/ React.createElement(
          "div",
          {
            class: "oercurr-blk-topbar-left"
          },
          /*#__PURE__*/ React.createElement(
            "span",
            {
              class: "oercurr-blk-topbar-left-count"
            },
            "Browse All ",
            attributes.curriculumlength,
            " Curriculums"
          )
        ),
        /*#__PURE__*/ React.createElement(
          "div",
          {
            class: "oercurr-blk-topbar-right"
          },
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oercurr-blk-topbar-display-box"
            },
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oercurr-blk-topbar-display-text"
              },
              /*#__PURE__*/ React.createElement(
                "span",
                null,
                "Show ",
                attributes.selper
              ),
              /*#__PURE__*/ React.createElement(
                "a",
                {
                  href: "#"
                },
                /*#__PURE__*/ React.createElement("i", {
                  class: "fa fa-th-list",
                  "aria-hidden": "true"
                })
              )
            ),
            /*#__PURE__*/ React.createElement(
              "ul",
              {
                class:
                  "oercurr-blk-topbar-display-option oercurr-blk-topbar-option"
              },
              attributes.displayOption.map((incr, index) => {
                if (attributes.selper == incr) {
                  return /*#__PURE__*/ React.createElement(
                    "li",
                    {
                      class: "selected"
                    },
                    /*#__PURE__*/ React.createElement(
                      "a",
                      {
                        href: "#",
                        ret: incr
                      },
                      incr
                    )
                  );
                } else {
                  return /*#__PURE__*/ React.createElement(
                    "li",
                    null,
                    /*#__PURE__*/ React.createElement(
                      "a",
                      {
                        href: "#",
                        ret: incr
                      },
                      incr
                    )
                  );
                }
              })
            )
          ),
          /*#__PURE__*/ React.createElement(
            "div",
            {
              class: "oercurr-blk-topbar-sort-box"
            },
            /*#__PURE__*/ React.createElement(
              "div",
              {
                class: "oercurr-blk-topbar-sort-text"
              },
              /*#__PURE__*/ React.createElement(
                "span",
                null,
                "Sort by: ",
                attributes.sortOption[0][attributes.selsrt]
              ),
              /*#__PURE__*/ React.createElement(
                "a",
                {
                  href: "#"
                },
                /*#__PURE__*/ React.createElement("i", {
                  class: "fa fa-sort",
                  "aria-hidden": "true"
                })
              )
            ),
            /*#__PURE__*/ React.createElement(
              "ul",
              {
                class:
                  "oercurr-blk-topbar-sort-option oercurr-blk-topbar-option"
              },
              Object.keys(attributes.sortOption[0]).map((key) => {
                if (attributes.selsrt == key) {
                  return /*#__PURE__*/ React.createElement(
                    "li",
                    {
                      class: "selected"
                    },
                    /*#__PURE__*/ React.createElement(
                      "a",
                      {
                        href: "#",
                        ret: key
                      },
                      attributes.sortOption[0][key]
                    )
                  );
                } else {
                  return /*#__PURE__*/ React.createElement(
                    "li",
                    null,
                    /*#__PURE__*/ React.createElement(
                      "a",
                      {
                        href: "#",
                        ret: key
                      },
                      attributes.sortOption[0][key]
                    )
                  );
                }
              })
            )
          )
        ),
        /*#__PURE__*/ React.createElement(
          "div",
          {
            id: "oer_curriculum_cur_blk_content_wrapper",
            class: "oercurr-blk-wrapper"
          },
          /*#__PURE__*/ React.createElement(
            "div",
            {
              id: "oercurr-blk-content_drop"
            },
            attributes.curriculums.map((post, index) => {
              let ctnt = post.content.replace(/<[^>]+>/g, "");

              if (ctnt.length <= 180) {
                ctnt = ctnt + "....";
              } else {
                ctnt = ctnt.substr(1, 180) + "...";
              }

              let grd = 0;
              let grdtxt = "";

              if (post.oer_curriculum_grades != "") {
                if (post.oer_curriculum_grades.length > 1) {
                  grdtxt = "Grades: ";
                  grd =
                    post.oer_curriculum_grades[0] +
                    "-" +
                    post.oer_curriculum_grades[
                      post.oer_curriculum_grades.length - 1
                    ];
                } else {
                  grdtxt = "Grade: ";
                  grd = post.oer_curriculum_grades;
                }
              } else {
                grdtxt = "";
                grd = "";
              }

              return /*#__PURE__*/ React.createElement(
                "div",
                {
                  class: "oercurr-blk-row"
                },
                /*#__PURE__*/ React.createElement(
                  "a",
                  {
                    href: post.link,
                    class: "oercurr-blk-left",
                    target: "_new",
                    rel: "noopener"
                  },
                  /*#__PURE__*/ React.createElement("img", {
                    src: post.featured_image_url,
                    alt: ""
                  })
                ),
                /*#__PURE__*/ React.createElement(
                  "div",
                  {
                    class: "oercurr-blk-right"
                  },
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "ttl"
                    },
                    /*#__PURE__*/ React.createElement(
                      "a",
                      {
                        href: post.link,
                        target: "_new",
                        rel: "noopener"
                      },
                      post.title
                    )
                  ),
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "desc"
                    },
                    ctnt
                  ),
                  /*#__PURE__*/ React.createElement(
                    "div",
                    {
                      class: "oercurr-tags tagcloud"
                    },
                    post.tagsv2.map((posttags, index) => {
                      let tgar = posttags.split("|");
                      return /*#__PURE__*/ React.createElement(
                        "span",
                        null,
                        /*#__PURE__*/ React.createElement(
                          "a",
                          {
                            href:
                              oercurr_cb_cgb_Global["base_url"] +
                              "/tag/" +
                              tgar[1],
                            alt: "",
                            class: "button",
                            target: "_new",
                            rel: "noopener"
                          },
                          tgar[0]
                        )
                      );
                    })
                  )
                )
              );
            })
          )
        )
      )
    );
  },
  example: {}
});