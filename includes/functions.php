<?php

$nc_plugin = NewsletterCampaign::get_instance();
$nc_plugin_slug = $nc_plugin->get_plugin_slug();

function is_array_empty($input) {
    $result = true;

    if (is_array($input) && count($input) > 0) {
        foreach ($input as $value) {
            $result = $result && is_array_empty($value);
        }
    } else {
      $result = empty($input);
    }

    return $result;
}


/**
 * Return true if the current screen is the desired screen
 * @param  str  $post_type  The name of the desired screen
 * @return bool             True: is on desired page, False: not
 */
function nc_check_screen($post_type) {
    // If not in campaign screen, exit
    $screen = get_current_screen();
    if ( $post_type !== $screen->post_type ) {
        return false;
    } else {
        return true;
    }
}


function nc_nest_array() {
    $nest_array = array();
    for ($i = 0; $i <= NC_MAX_NEST_DEPTH; $i++) {
        $nest_array[] = $i;
    }
    return $nest_array;
}


function nc_html_attributes($tag_name) {
    $attributes = [];

    switch ($tag_name) {
        case 'html':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_html', array(
                array(
                    'name'  => 'nc-shortcode-arg-html-xmlns',
                    'arg'   => 'xmlns',
                    'title' => 'xmlns'
                )
            ));
            break;

        case 'head':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_head', array());
            break;

        case 'body':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_body', array());
            break;

        case 'base':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_base', array(
                array(
                    'name'  => 'nc-shortcode-arg-base-href',
                    'arg'   => 'href',
                    'title' => 'href'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-base-target',
                    'arg'   => 'target',
                    'title' => 'target',
                    'type'  => 'select',
                    'values'=>  array('_blank', '_parent', '_self', '_top')
                )
            ));
            break;

        case 'link':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_link', array(
                array(
                    'name'  => 'nc-shortcode-arg-link-charset',
                    'arg'   => 'charset',
                    'title' => 'charset'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-href',
                    'arg'   => 'href',
                    'title' => 'href'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-media',
                    'arg'   => 'media',
                    'title' => 'media'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-rel',
                    'arg'   => 'rel',
                    'title' => 'rel',
                    'type'  => 'select',
                    'values'=> array('alternate', 'archives', 'author', 'bookmark', 'external', 'first', 'help', 'icon', 'last', 'license', 'next', 'nofollow', 'noreferrer', 'pingback', 'prefetch', 'prev', 'search', 'sidebar', 'stylesheet', 'tag', 'up')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-type',
                    'arg'   => 'type',
                    'title' => 'type'
                )
            ));
            break;

        case 'meta':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_meta', array(
                array(
                    'name'  => 'nc-shortcode-arg-meta-content',
                    'arg'   => 'content',
                    'title' => 'content'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-meta-http-equiv',
                    'arg'   => 'http_equiv',
                    'title' => 'http-equiv',
                    'type'  => 'select',
                    'values'=>  array('content-type', 'default-style', 'refresh')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-meta-name',
                    'arg'   => 'name',
                    'title' => 'name',
                    'type'  => 'select',
                    'values'=>  array('viewport', 'application-name', 'author', 'description', 'generator', 'keywords')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-meta-scheme',
                    'arg'   => 'scheme',
                    'title' => 'scheme'
                )
            ));
            break;

        case 'style':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_style', array(
                array(
                    'name'  => 'nc-shortcode-arg-style-media',
                    'arg'   => 'media',
                    'title' => 'media'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-style-type',
                    'arg'   => 'type',
                    'title' => 'type',
                    'type'  => 'select',
                    'values'=> array('text/css')
                )
            ));
            break;

        case 'p':
            $attributes = apply_filters('newsletter_campaign_html_attributes_p', array(
                array(
                    'name'  => 'nc-shortcode-arg-p-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'h1':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h1', array(
                array(
                    'name'  => 'nc-shortcode-arg-h1-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'h2':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h2', array(
                array(
                    'name'  => 'nc-shortcode-arg-h2-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'h3':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h3', array(
                array(
                    'name'  => 'nc-shortcode-arg-h3-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'h4':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h4', array(
                array(
                    'name'  => 'nc-shortcode-arg-h4-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'h5':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h5', array(
                array(
                    'name'  => 'nc-shortcode-arg-h5-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'h6':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h6', array(
                array(
                    'name'  => 'nc-shortcode-arg-h6-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                )
            ));
            break;

        case 'dl':
            $attributes = apply_filters('newsletter_campaign_html_attributes_dl', array());
            break;

        case 'dt':
            $attributes = apply_filters('newsletter_campaign_html_attributes_dt', array());
            break;

        case 'dd':
            $attributes = apply_filters('newsletter_campaign_html_attributes_dd', array());
            break;

        case 'ol':
            $attributes = apply_filters('newsletter_campaign_html_attributes_ol', array(
                array(
                    'name'  => 'nc-shortcode-arg-ol-start',
                    'arg'   => 'start',
                    'title' => 'start'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-ol-type',
                    'arg'   => 'type',
                    'title' => 'type',
                    'type'  => 'select',
                    'values'=> array('1', 'a', 'A', 'i', 'I')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-ol-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'ul':
            $attributes = apply_filters('newsletter_campaign_html_attributes_ul', array(
                array(
                    'name'  => 'nc-shortcode-arg-ul-type',
                    'arg'   => 'type',
                    'title' => 'type',
                    'type'  => 'select',
                    'values'=> array('disc', 'square', 'circle')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-ul-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'li':
            $attributes = apply_filters('newsletter_campaign_html_attributes_li', array(
                array(
                    'name'  => 'nc-shortcode-arg-li-type',
                    'arg'   => 'type',
                    'title' => 'type',
                    'type'  => 'select',
                    'values'=> array('1', 'A', 'a', 'I', 'i', 'disc', 'square', 'circle')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-li-value',
                    'arg'   => 'value',
                    'title' => 'value'
                ),
                array(
                    'name'      => 'nc-shortcode-arg-li-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'div':
            $attributes = apply_filters('newsletter_campaign_html_attributes_div', array(
                array(
                    'name'  => 'nc-shortcode-arg-div-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-div-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'hr':
            $attributes = apply_filters('newsletter_campaign_html_attributes_hr', array(
                array(
                    'name'  => 'nc-shortcode-arg-hr-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center')
                )
            ));
            break;

        case 'a':
            $attributes = apply_filters('newsletter_campaign_html_attributes_a', array(
                array(
                    'name'  => 'nc-shortcode-arg-a-href',
                    'arg'   => 'href',
                    'title' => 'href'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-target',
                    'arg'   => 'target',
                    'title' => 'target',
                    'type'  => 'select',
                    'values'=> array('_blank', '_self', '_parent', '_top')
                )
            ));
            break;

        case 'em':
            $attributes = apply_filters('newsletter_campaign_html_attributes_em', array());
            break;

        case 'strong':
            $attributes = apply_filters('newsletter_campaign_html_attributes_strong', array());
            break;

        case 'span':
            $attributes = apply_filters('newsletter_campaign_html_attributes_span', array());
            break;

        case 'br':
            $attributes = apply_filters('newsletter_campaign_html_attributes_br', array());
            break;

        case 'sub':
            $attributes = apply_filters('newsletter_campaign_html_attributes_sub', array());
            break;

        case 'sup':
            $attributes = apply_filters('newsletter_campaign_html_attributes_sup', array());
            break;

        case 'img':
            $attributes = apply_filters('newsletter_campaign_html_attributes_img', array(
                array(
                    'name'  => 'nc-shortcode-arg-img-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('top', 'bottom', 'middle', 'left', 'right')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-alt',
                    'arg'   => 'alt',
                    'title' => 'alt'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-height',
                    'arg'   => 'height',
                    'title' => 'height'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-src',
                    'arg'   => 'src',
                    'title' => 'src'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'table':
            $attributes = apply_filters('newsletter_campaign_html_attributes_table', array(
                array(
                    'name'  => 'nc-shortcode-arg-table-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'center', 'right')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-table-cellpadding',
                    'arg'   => 'cellpadding',
                    'title' => 'cellpadding'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-table-cellspacing',
                    'arg'   => 'cellspacing',
                    'title' => 'cellspacing'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-table-width',
                    'arg'   => 'width',
                    'title' => 'width'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-table-border',
                    'arg'   => 'border',
                    'title' => 'border'
                ),
                array(
                    'name'      => 'nc-shortcode-arg-table-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'tr':
            $attributes = apply_filters('newsletter_campaign_html_attributes_tr', array(
                array(
                    'name'  => 'nc-shortcode-arg-tr-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tr-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-tr-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'th':
            $attributes = apply_filters('newsletter_campaign_html_attributes_th', array(
                array(
                    'name'  => 'nc-shortcode-arg-th-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-colspan',
                    'arg'   => 'colspan',
                    'title' => 'colspan'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-height',
                    'arg'   => 'height',
                    'title' => 'height'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-nowrap',
                    'arg'   => 'nowrap',
                    'title' => 'nowrap',
                    'type'  => 'bool'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-rowspan',
                    'arg'   => 'rowspan',
                    'title' => 'rowspan'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-width',
                    'arg'   => 'width',
                    'title' => 'width'
                ),
                array(
                    'name'      => 'nc-shortcode-arg-th-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'td':
            $attributes = apply_filters('newsletter_campaign_html_attributes_td', array(
                array(
                    'name'  => 'nc-shortcode-arg-td-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-colspan',
                    'arg'   => 'colspan',
                    'title' => 'colspan'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-height',
                    'arg'   => 'height',
                    'title' => 'height'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-nowrap',
                    'arg'   => 'nowrap',
                    'title' => 'nowrap',
                    'type'  => 'bool'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-rowspan',
                    'arg'   => 'rowspan',
                    'title' => 'rowspan'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-width',
                    'arg'   => 'width',
                    'title' => 'width'
                ),
                array(
                    'name'      => 'nc-shortcode-arg-td-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'colgroup':
            $attributes = apply_filters('newsletter_campaign_html_attributes_colgroup', array(
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-span',
                    'arg'   => 'span',
                    'title' => 'span'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'col':
            $attributes = apply_filters('newsletter_campaign_html_attributes_col', array(
                array(
                    'name'  => 'nc-shortcode-arg-col-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-col-span',
                    'arg'   => 'span',
                    'title' => 'span'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-col-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-col-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'caption':
            $attributes = apply_filters('newsletter_campaign_html_attributes_caption', array(
                array(
                    'name'  => 'nc-shortcode-arg-caption-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'top', 'bottom')
                )
            ));
            break;

        case 'thead':
            $attributes = apply_filters('newsletter_campaign_html_attributes_thead', array(
                array(
                    'name'  => 'nc-shortcode-arg-thead-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-thead-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-thead-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'tbody':
            $attributes = apply_filters('newsletter_campaign_html_attributes_tbody', array(
                array(
                    'name'  => 'nc-shortcode-arg-tbody-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tbody-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-tbody-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        case 'tfoot':
            $attributes = apply_filters('newsletter_campaign_html_attributes_tfoot', array(
                array(
                    'name'  => 'nc-shortcode-arg-tfoot-align',
                    'arg'   => 'align',
                    'title' => 'align',
                    'type'  => 'select',
                    'values'=> array('left', 'right', 'center', 'justify', 'char')
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tfoot-valign',
                    'arg'   => 'valign',
                    'title' => 'valign',
                    'type'  => 'select',
                    'values'=> array('top', 'middle', 'bottom', 'baseline')
                ),
                array(
                    'name'      => 'nc-shortcode-arg-tfoot-nesting',
                    'arg'       => 'nesting',
                    'title'     => __('How many levels deep nested within same element?'),
                    'type'      => 'select',
                    'values'    => nc_nest_array(),
                    'default'   => 0
                )
            ));
            break;

        default: // Defaults to general attributes
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_general', array(
                array(
                    'name'  => 'nc-shortcode-arg-class',
                    'arg'   => 'class',
                    'title' => 'class'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-id',
                    'arg'   => 'id',
                    'title' => 'id'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-style',
                    'arg'   => 'style',
                    'title' => 'style'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-title',
                    'arg'   => 'title',
                    'title' => 'title'
                )
            ));
            break;
    }

    return $attributes;
}



/**
 * Return an array of matching keys
 * @param  arr $search_array    The array to search through
 * @param  str $key_to_search   Key to perform the search on
 * @param  str $key_to_return   The value of this key will be added to the return array
 * @param  str $value_to_search Optional: return key will only be added if this value matches the value of the search key
 * @return arr                  An array of strings
 */
function nc_fetch_array_keys($search_array, $key_to_search, $key_to_return, $value_to_search = null) {

    $return_array = array();

    foreach ($search_array as $key => $value) {
        if (array_key_exists($key_to_search, $value)) {
            if (isset($value_to_search)) {
                if ($value_to_search === $value[$key_to_search]) {
                    $return_array[] = $value[$key_to_return];
                }
            } else {
                $return_array[] = $value[$key_to_return];
            }
        }

        if (is_array($value)) {
            $new_return_array = nc_fetch_array_keys($value, $key_to_search, $key_to_return, $value_to_search);
            $return_array = array_merge($return_array, $new_return_array);
        }
    }

    return $return_array;
}

/*   TEMP FUNC TESTER
$tags = nc_get_html_tags();
$shortcode_tags = nc_fetch_array_keys($tags, 'shortcode', 'shortcode');
print_r($shortcode_tags);*/

function nc_get_html_tags() {
    $options = get_option( 'nc_settings' );
    $subscriber_list_cat_args = apply_filters( 'newsletter_campaign_subscriber_list_cat_args', array(
        'taxonomy'  => 'subscriber_list'
    ));

    // Fetch the array of subscriber lists, prepending with 'all lists' option
    $subscriber_list_cats = array_merge(array(array('name' => __('All lists'), 'slug' => 'nc_all')), get_categories($subscriber_list_cat_args));

    $html_tags = apply_filters( 'newsletter_campaign_html_tags', array(
        array(
            'title'             => __('HTML', $nc_plugin_slug),
            'class'             => 'nc-button-bar__parent',
            'children'          => array(
                array(
                    'title'             => __('Document structure', $nc_plugin_slug),
                    'class'             => 'nc-button-bar__parent',
                    'instance_include'  => 'newsletter_campaign_template_base-html',
                    'children'          => array(
                        array(
                            'title'     => 'Doctype',
                            'id'        => 'nc-button-doctype',
                            'class'     => 'nc-button-bar__button',
                            'shortcode' => 'nc_doctype',
                            'args'      => array(
                                array(
                                    'name'  => 'nc-shortcode-arg-html-doctype',
                                    'arg'   => 'doctype',
                                    'title' => 'Doctype',
                                    'type'  => 'select',
                                    'values'=>  array(
                                        array(
                                            'name' => 'HTML5',
                                            'value'=> 'html5'
                                        ),
                                        array(
                                            'name' => 'HTML 4.01 Strict',
                                            'value'=> 'html-4-01-strict'
                                        ),
                                        array(
                                            'name' => 'HTML 4.01 Transitional',
                                            'value'=> 'html-4-01-transitional'
                                        ),
                                        array(
                                            'name' => 'XHTML 1.0 Strict',
                                            'value'=> 'xhtml-1-strict'
                                        ),
                                        array(
                                            'name' => 'XHTML 1.0 Transitional',
                                            'value'=> 'xhtml-1-transitional'
                                        ),
                                    ),
                                    'key'   => 'name',
                                    'value' => 'value'
                                )
                            )
                        ),
                        array(
                            'title'             => 'HTML',
                            'id'                => 'nc-button-html',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_html',
                            'enclosing'         => true,
                            'enclosing_text'    => __('HTML content', $nc_plugin_slug),
                            'args'              => array_merge(nc_html_attributes('html'), nc_html_attributes())
                        ),
                        array(
                            'title'             => 'Head',
                            'id'                => 'nc-button-head',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_head',
                            'enclosing'         => true,
                            /* translators: do not translate ´Head´ - is an HTML element name */
                            'enclosing_text'    => __('Head content', $nc_plugin_slug),
                            'args'              => array_merge(nc_html_attributes('head'), nc_html_attributes())
                        ),
                        array(
                            'title'             => 'Body',
                            'id'                => 'nc-button-body',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_body',
                            'enclosing'         => true,
                            /* translators: do not translate ´Body´ - is an HTML element name */
                            'enclosing_text'    => __('Body content', $nc_plugin_slug),
                            'args'              => array_merge(nc_html_attributes('body'), nc_html_attributes())
                        )
                    )
                ),
                array(
                    /* translators: do not translate ´Head´ - is an HTML element name */
                    'title'             => __('Head elements', $nc_plugin_slug),
                    'class'             => 'nc-button-bar__parent',
                    'instance_include'  => 'newsletter_campaign_template_base-html',
                    'children'          => array(
                        array(
                            'title'             => 'Base',
                            'id'                => 'nc-button-base',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_base',
                            'args'              => array_merge(nc_html_attributes('base'), nc_html_attributes())
                        ),
                        array(
                            'title'             => 'Link',
                            'id'                => 'nc-button-link',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_link',
                            'args'              => array_merge(nc_html_attributes('link'), nc_html_attributes())
                        ),
                        array(
                            'title'             => 'Meta',
                            'id'                => 'nc-button-meta',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_meta',
                            'args'              => array_merge(nc_html_attributes('meta'), nc_html_attributes())
                        ),
                        array(
                            'title'             => 'Style',
                            'id'                => 'nc-button-style',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_style',
                            'enclosing'         => true,
                            /* translators: do not translate ´Style´ - is an HTML element name */
                            'enclosing_text'    => __('Style content', $nc_plugin_slug),
                            'args'              => array_merge(nc_html_attributes('style'), nc_html_attributes())
                        ),
                        array(
                            'title'             => 'Title',
                            'id'                => 'nc-button-title',
                            'class'             => 'nc-button-bar__button',
                            'shortcode'         => 'nc_title',
                            'enclosing'         => true,
                            /* translators: do not translate ´Title´ - is an HTML element name */
                            'enclosing_text'    => __('Title content', $nc_plugin_slug)
                        )
                    )
                ),
                array(
                    /* translators: do not translate ´Body´ - is an HTML element name */
                    'title'             => __('Body elements', $nc_plugin_slug),
                    'class'             => 'nc-button-bar__parent',
                    'children'          => array(
                        array(
                            'title'     => __('Block elements', $nc_plugin_slug),
                            'class'     => 'nc-button-bar__parent',
                            'children'  => array(
                                array(
                                    'title'             => 'p',
                                    'id'                => 'nc-button-p',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_p',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´p´ - is an HTML element name */
                                    'enclosing_text'    => __('p content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('p'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'h1',
                                    'id'                => 'nc-button-h1',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_h1',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´h1´ - is an HTML element name */
                                    'enclosing_text'    => __('h1 content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('h1'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'h2',
                                    'id'                => 'nc-button-h2',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_h2',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´h2´ - is an HTML element name */
                                    'enclosing_text'    => __('h2 content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('h2'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'h3',
                                    'id'                => 'nc-button-h3',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_h3',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´h3´ - is an HTML element name */
                                    'enclosing_text'    => __('h3 content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('h3'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'h4',
                                    'id'                => 'nc-button-h4',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_h4',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´h4´ - is an HTML element name */
                                    'enclosing_text'    => __('h4 content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('h4'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'h5',
                                    'id'                => 'nc-button-h5',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_h5',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´h5´ - is an HTML element name */
                                    'enclosing_text'    => __('h5 content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('h5'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'h6',
                                    'id'                => 'nc-button-h6',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_h6',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´h6´ - is an HTML element name */
                                    'enclosing_text'    => __('h6 content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('h6'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'dl',
                                    'id'                => 'nc-button-dl',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_dl',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´dl´ - is an HTML element name */
                                    'enclosing_text'    => __('dl content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('dl'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'dt',
                                    'id'                => 'nc-button-dt',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_dt',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´dt´ - is an HTML element name */
                                    'enclosing_text'    => __('dt content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('dt'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'dd',
                                    'id'                => 'nc-button-dd',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_dd',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´dd´ - is an HTML element name */
                                    'enclosing_text'    => __('dd content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('dd'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'ol',
                                    'id'                => 'nc-button-ol',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_ol',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´ol´ - is an HTML element name */
                                    'enclosing_text'    => __('ol content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('ol'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'ul',
                                    'id'                => 'nc-button-ul',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_ul',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´ul´ - is an HTML element name */
                                    'enclosing_text'    => __('ul content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('ul'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'li',
                                    'id'                => 'nc-button-li',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_li',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´li´ - is an HTML element name */
                                    'enclosing_text'    => __('li content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('li'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'div',
                                    'id'                => 'nc-button-div',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_div',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´div´ - is an HTML element name */
                                    'enclosing_text'    => __('div content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('div'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'hr',
                                    'id'                => 'nc-button-hr',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_hr',
                                    'args'              => array_merge(nc_html_attributes('hr'), nc_html_attributes())
                                )
                            )
                        ),
                        array(
                            'title'     => __('Inline elements', $nc_plugin_slug),
                            'class'     => 'nc-button-bar__parent',
                            'children'  => array(
                                array(
                                    'title'             => 'a',
                                    'id'                => 'nc-button-a',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_a',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´a´ - is an HTML element name */
                                    'enclosing_text'    => __('a content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('a'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'em',
                                    'id'                => 'nc-button-em',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_em',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´em´ - is an HTML element name */
                                    'enclosing_text'    => __('em content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('em'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'strong',
                                    'id'                => 'nc-button-strong',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_strong',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´strong´ - is an HTML element name */
                                    'enclosing_text'    => __('strong content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('strong'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'span',
                                    'id'                => 'nc-button-span',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_span',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´span´ - is an HTML element name */
                                    'enclosing_text'    => __('span content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('span'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'br',
                                    'id'                => 'nc-button-br',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_br',
                                    'args'              => array_merge(nc_html_attributes('br'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'sub',
                                    'id'                => 'nc-button-sub',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_sub',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´sub´ - is an HTML element name */
                                    'enclosing_text'    => __('sub content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('sub'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'sup',
                                    'id'                => 'nc-button-sup',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_sup',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´sup´ - is an HTML element name */
                                    'enclosing_text'    => __('sup content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('sup'), nc_html_attributes())
                                )
                            )
                        ),
                        array(
                            'title'     => __('Images', $nc_plugin_slug),
                            'class'     => 'nc-button-bar__parent',
                            'children'  => array(
                                array(
                                    'title'             => 'img',
                                    'id'                => 'nc-button-img',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_img',
                                    'args'              => array_merge(nc_html_attributes('img'), nc_html_attributes())
                                )
                            )
                        ),
                        array(
                            'title'     => __('Tables', $nc_plugin_slug),
                            'class'     => 'nc-button-bar__parent',
                            'children'  => array(
                                array(
                                    'title'             => 'table',
                                    'id'                => 'nc-button-table',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_table',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´table´ - is an HTML element name */
                                    'enclosing_text'    => __('table content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('table'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'tr',
                                    'id'                => 'nc-button-tr',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_tr',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´tr´ - is an HTML element name */
                                    'enclosing_text'    => __('tr content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('tr'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'th',
                                    'id'                => 'nc-button-th',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_th',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´th´ - is an HTML element name */
                                    'enclosing_text'    => __('th content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('th'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'td',
                                    'id'                => 'nc-button-td',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_td',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´td´ - is an HTML element name */
                                    'enclosing_text'    => __('td content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('td'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'colgroup',
                                    'id'                => 'nc-button-colgroup',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_colgroup',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´colgroup´ - is an HTML element name */
                                    'enclosing_text'    => __('colgroup content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('colgroup'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'col',
                                    'id'                => 'nc-button-col',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_col',
                                    'args'              => array_merge(nc_html_attributes('col'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'caption',
                                    'id'                => 'nc-button-caption',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_caption',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´caption´ - is an HTML element name */
                                    'enclosing_text'    => __('caption content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('caption'), nc_html_attributes())
                                ),
                                array(
                                    'title'             => 'thead',
                                    'id'                => 'nc-button-thead',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_thead',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´thead´ - is an HTML element name */
                                    'enclosing_text'    => __('thead content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('thead'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'tbody',
                                    'id'                => 'nc-button-tbody',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_tbody',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´tbody´ - is an HTML element name */
                                    'enclosing_text'    => __('tbody content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('tbody'), nc_html_attributes()),
                                    'nest'              => true
                                ),
                                array(
                                    'title'             => 'tfoot',
                                    'id'                => 'nc-button-tfoot',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_tfoot',
                                    'enclosing'         => true,
                                    /* translators: do not translate ´tfoot´ - is an HTML element name */
                                    'enclosing_text'    => __('tfoot content', $nc_plugin_slug),
                                    'args'              => array_merge(nc_html_attributes('tfoot'), nc_html_attributes()),
                                    'nest'              => true
                                )
                            )
                        ),
                        array(
                            'title'     => __('Comments', $nc_plugin_slug),
                            'class'     => 'nc-button-bar__parent',
                            'children'  => array(
                                array(
                                    'title'             => 'comment',
                                    'id'                => 'nc-button-comment',
                                    'class'             => 'nc-button-bar__button',
                                    'shortcode'         => 'nc_comment',
                                    'enclosing'         => true,
                                    'enclosing_text'    => __('comment content', $nc_plugin_slug)
                                )
                            )
                        )
                    )
                )
            )
        ),
        array(
            'title'             => __('Email functionality', $nc_plugin_slug),
            'class'             => 'nc-button-bar__parent',
            'instance_include'  => 'newsletter_campaign_template_base-html',
            'children'          => array(
                array(
                    'title'             => __('Output posts', $nc_plugin_slug),
                    'id'                => 'nc-button-posts',
                    'class'             => 'nc-button-bar__button',
                    'shortcode'         => 'nc_posts'
                ),
                array(
                    'title'             => __('View in browser', $nc_plugin_slug),
                    'id'                => 'nc-button-view-browser',
                    'class'             => 'nc-button-bar__button',
                    'shortcode'         => 'nc_browser_link',
                    'enclosing'         => true,
                    'enclosing_text'    => __('View in browser', $nc_plugin_slug),
                ),
                array(
                    'title'             => __('Unsubscribe link', $nc_plugin_slug),
                    'id'                => 'nc-button-unsubscribe',
                    'class'             => 'nc-button-bar__button',
                    'shortcode'         => 'nc_unsubscribe_link',
                    'enclosing'         => true,
                    'enclosing_text'    => __('Unsubscribe text', $nc_plugin_slug),
                    'args'              => array(
                        array(
                            'name'  => 'nc-shortcode-arg-unsubscribe-list',
                            'arg'   => 'list',
                            'title' => __('Subscriber list', $nc_plugin_slug),
                            'type'  => 'select',
                            'values'=>  $subscriber_list_cats,
                            'key'   => 'name',
                            'value' => 'slug'
                        )
                    )
                )
            )
        ),
        array(
            'title'     => __('Personal fields', $nc_plugin_slug),
            'class'     => 'nc-button-bar__parent',
            'children'  => array(
                array(
                    'title'     => __('Name', $nc_plugin_slug),
                    'id'        => 'nc-button-personal-name',
                    'class'     => 'nc-button-bar__button',
                    'shortcode' => 'nc_name',
                    'args'      => array(
                        array(
                            'name'  => 'nc-shortcode-arg-name-before',
                            'arg'   => 'before',
                            'title' => __('Before', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-name-after',
                            'arg'   => 'after',
                            'title' => __('After', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-name-noval',
                            'arg'   => 'noval',
                            'title' => __('If no value', $nc_plugin_slug)
                        )
                    )
                ),
                array(
                    'title'     => __('Email', $nc_plugin_slug),
                    'id'        => 'nc-button-personal-email',
                    'class'     => 'nc-button-bar__button',
                    'shortcode' => 'nc_email',
                    'args'      => array(
                        array(
                            'name'  => 'nc-shortcode-arg-email-before',
                            'arg'   => 'before',
                            'title' => __('Before', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-email-after',
                            'arg'   => 'after',
                            'title' => __('After', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-email-noval',
                            'arg'   => 'noval',
                            'title' => __('If no value', $nc_plugin_slug)
                        )
                    )
                ),
                array(
                    'title'     => __('Extra info', $nc_plugin_slug),
                    'id'        => 'nc-button-personal-extra',
                    'class'     => 'nc-button-bar__button',
                    'shortcode' => 'nc_extra',
                    'args'      => array(
                        array(
                            'name'  => 'nc-shortcode-arg-extra-before',
                            'arg'   => 'before',
                            'title' => __('Before', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-extra-after',
                            'arg'   => 'after',
                            'title' => __('After', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-extra-noval',
                            'arg'   => 'noval',
                            'title' => __('If no value', $nc_plugin_slug)
                        )
                    )
                )
            )
        ),
        array(
            'title'             => __('Post', $nc_plugin_slug),
            'class'             => 'nc-button-bar__parent',
            'instance_exclude'  => 'newsletter_campaign_template_base-html',
            'children'          => array(
                array(
                    'title'     => __('Post title', $nc_plugin_slug),
                    'id'        => 'nc-button-post-title',
                    'class'     => 'nc-button-bar__button',
                    'shortcode' => 'nc_post_title'
                ),
                array(
                    'title'     => __('Post body', $nc_plugin_slug),
                    'id'        => 'nc-button-post-body',
                    'class'     => 'nc-button-bar__button',
                    'shortcode' => 'nc_post_body'
                ),
                array(
                    'title'     => __('Featured image', $nc_plugin_slug),
                    'id'        => 'nc-button-feat-image',
                    'class'     => 'nc-button-bar__button',
                    'shortcode' => 'nc_feat_image',
                    'args'      => array(
                        array(
                            'name'  => 'nc-shortcode-arg-feat-img-size',
                            'arg'   => 'size',
                            'title' => __('Size', $nc_plugin_slug),
                            'type'  => 'select',
                            'values'=>  get_intermediate_image_sizes()
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-feat-img-width',
                            'arg'   => 'width',
                            'title' => __('Width', $nc_plugin_slug)
                        ),
                        array(
                            'name'  => 'nc-shortcode-arg-feat-img-height',
                            'arg'   => 'height',
                            'title' => __('Height', $nc_plugin_slug)
                        ),
                    )
                ),
                array(
                    'title'             => __('Post Divider', $nc_plugin_slug),
                    'id'                => 'nc-button-divider',
                    'class'             => 'nc-button-bar__button',
                    'shortcode'         => $options['nc_shortcode_divider']
                )
            )
        )
    ));
    return $html_tags;
}