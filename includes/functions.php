<?php

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
    for ($i = 1; $i <= NC_MAX_NEST_DEPTH; $i++) {
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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
                    'values'    => $nest_array,
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