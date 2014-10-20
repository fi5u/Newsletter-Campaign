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


function nc_html_attributes($tag_name) {
    $attributes = [];

    switch ($tag_name) {
        case 'html':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_html', array(
                array(
                    'name'  => 'nc-shortcode-arg-html-xmlns',
                    'arg'   => 'xmlns',
                    'title' => 'XMLNS'
                )
            ));
            break;

        case 'head':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_head', array());
            break;

        case 'body':
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_body', array(
                array(
                    'name'  => 'nc-shortcode-arg-body-alink',
                    'arg'   => 'alink',
                    'title' => 'alink'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-body-background',
                    'arg'   => 'background',
                    'title' => 'background'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-body-bgcolor',
                    'arg'   => 'bgcolor',
                    'title' => 'bgcolor'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-body-link',
                    'arg'   => 'link',
                    'title' => 'link'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-body-text',
                    'arg'   => 'text',
                    'title' => 'text'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-body-vlink',
                    'arg'   => 'vlink',
                    'title' => 'vlink'
                )
            ));
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
                    'title' => 'target'
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
                    'name'  => 'nc-shortcode-arg-link-hreflang',
                    'arg'   => 'hreflang',
                    'title' => 'hreflang'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-media',
                    'arg'   => 'media',
                    'title' => 'media'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-rel',
                    'arg'   => 'rel',
                    'title' => 'rel'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-rev',
                    'arg'   => 'rev',
                    'title' => 'rev'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-link-target',
                    'arg'   => 'target',
                    'title' => 'target'
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
                    'name'  => 'nc-shortcode-arg-meta-charset',
                    'arg'   => 'charset',
                    'title' => 'charset'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-meta-content',
                    'arg'   => 'content',
                    'title' => 'content'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-meta-http-equiv',
                    'arg'   => 'http_equiv',
                    'title' => 'http-equiv'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-meta-name',
                    'arg'   => 'name',
                    'title' => 'name'
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
                    'title' => 'type'
                )
            ));
            break;

        case 'p':
            $attributes = apply_filters('newsletter_campaign_html_attributes_p', array(
                array(
                    'name'  => 'nc-shortcode-arg-p-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'h1':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h1', array(
                array(
                    'name'  => 'nc-shortcode-arg-h1-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'h2':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h2', array(
                array(
                    'name'  => 'nc-shortcode-arg-h2-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'h3':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h3', array(
                array(
                    'name'  => 'nc-shortcode-arg-h3-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'h4':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h4', array(
                array(
                    'name'  => 'nc-shortcode-arg-h4-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'h5':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h5', array(
                array(
                    'name'  => 'nc-shortcode-arg-h5-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'h6':
            $attributes = apply_filters('newsletter_campaign_html_attributes_h6', array(
                array(
                    'name'  => 'nc-shortcode-arg-h6-align',
                    'arg'   => 'align',
                    'title' => 'align'
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
                    'title' => 'type'
                )
            ));
            break;

        case 'ul':
            $attributes = apply_filters('newsletter_campaign_html_attributes_ul', array(
                array(
                    'name'  => 'nc-shortcode-arg-ul-type',
                    'arg'   => 'type',
                    'title' => 'type'
                )
            ));
            break;

        case 'li':
            $attributes = apply_filters('newsletter_campaign_html_attributes_li', array(
                array(
                    'name'  => 'nc-shortcode-arg-li-type',
                    'arg'   => 'type',
                    'title' => 'type'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-li-value',
                    'arg'   => 'value',
                    'title' => 'value'
                )
            ));
            break;

        case 'address':
            $attributes = apply_filters('newsletter_campaign_html_attributes_address', array());
            break;

        case 'blockquote':
            $attributes = apply_filters('newsletter_campaign_html_attributes_blockquote', array(
                array(
                    'name'  => 'nc-shortcode-arg-blockquote-cite',
                    'arg'   => 'cite',
                    'title' => 'cite'
                )
            ));
            break;

        case 'del':
            $attributes = apply_filters('newsletter_campaign_html_attributes_del', array(
                array(
                    'name'  => 'nc-shortcode-arg-del-cite',
                    'arg'   => 'cite',
                    'title' => 'cite'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-del-datetime',
                    'arg'   => 'datetime',
                    'title' => 'datetime'
                )
            ));
            break;

        case 'div':
            $attributes = apply_filters('newsletter_campaign_html_attributes_div', array(
                array(
                    'name'  => 'nc-shortcode-arg-div-align',
                    'arg'   => 'align',
                    'title' => 'align'
                )
            ));
            break;

        case 'hr':
            $attributes = apply_filters('newsletter_campaign_html_attributes_hr', array(
                array(
                    'name'  => 'nc-shortcode-arg-hr-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-hr-noshade',
                    'arg'   => 'noshade',
                    'title' => 'noshade'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-hr-size',
                    'arg'   => 'size',
                    'title' => 'size'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-hr-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'ins':
            $attributes = apply_filters('newsletter_campaign_html_attributes_ins', array(
                array(
                    'name'  => 'nc-shortcode-arg-ins-cite',
                    'arg'   => 'cite',
                    'title' => 'cite'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-ins-datetime',
                    'arg'   => 'datetime',
                    'title' => 'datetime'
                )
            ));
            break;

        case 'pre':
            $attributes = apply_filters('newsletter_campaign_html_attributes_pre', array(
                array(
                    'name'  => 'nc-shortcode-arg-pre-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'a':
            $attributes = apply_filters('newsletter_campaign_html_attributes_a', array(
                array(
                    'name'  => 'nc-shortcode-arg-a-charset',
                    'arg'   => 'charset',
                    'title' => 'charset'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-href',
                    'arg'   => 'href',
                    'title' => 'href'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-hreflang',
                    'arg'   => 'hreflang',
                    'title' => 'hreflang'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-name',
                    'arg'   => 'name',
                    'title' => 'name'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-rel',
                    'arg'   => 'rel',
                    'title' => 'rel'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-rev',
                    'arg'   => 'rev',
                    'title' => 'rev'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-a-target',
                    'arg'   => 'target',
                    'title' => 'target'
                )
            ));
            break;

        case 'abbr':
            $attributes = apply_filters('newsletter_campaign_html_attributes_abbr', array());
            break;

        case 'dfn':
            $attributes = apply_filters('newsletter_campaign_html_attributes_dfn', array());
            break;

        case 'em':
            $attributes = apply_filters('newsletter_campaign_html_attributes_em', array());
            break;

        case 'strong':
            $attributes = apply_filters('newsletter_campaign_html_attributes_strong', array());
            break;

        case 'code':
            $attributes = apply_filters('newsletter_campaign_html_attributes_code', array());
            break;

        case 'b':
            $attributes = apply_filters('newsletter_campaign_html_attributes_b', array());
            break;

        case 'i':
            $attributes = apply_filters('newsletter_campaign_html_attributes_i', array());
            break;

        case 'u':
            $attributes = apply_filters('newsletter_campaign_html_attributes_u', array());
            break;

        case 'small':
            $attributes = apply_filters('newsletter_campaign_html_attributes_small', array());
            break;

        case 'big':
            $attributes = apply_filters('newsletter_campaign_html_attributes_big', array());
            break;

        case 'font':
            $attributes = apply_filters('newsletter_campaign_html_attributes_font', array(
                array(
                    'name'  => 'nc-shortcode-arg-font-color',
                    'arg'   => 'color',
                    'title' => 'color'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-font-face',
                    'arg'   => 'face',
                    'title' => 'face'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-font-size',
                    'arg'   => 'size',
                    'title' => 'size'
                )
            ));
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
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-alt',
                    'arg'   => 'alt',
                    'title' => 'alt'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-border',
                    'arg'   => 'border',
                    'title' => 'border'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-height',
                    'arg'   => 'height',
                    'title' => 'height'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-hspace',
                    'arg'   => 'hspace',
                    'title' => 'hspace'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-src',
                    'arg'   => 'src',
                    'title' => 'src'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-img-vspace',
                    'arg'   => 'vspace',
                    'title' => 'vspace'
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
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-table-bgcolor',
                    'arg'   => 'bgcolor',
                    'title' => 'bgcolor'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-table-border',
                    'arg'   => 'border',
                    'title' => 'border'
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
                )
            ));
            break;

        case 'tr':
            $attributes = apply_filters('newsletter_campaign_html_attributes_tr', array(
                array(
                    'name'  => 'nc-shortcode-arg-tr-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tr-bgcolor',
                    'arg'   => 'bgcolor',
                    'title' => 'bgcolor'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tr-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
                )
            ));
            break;

        case 'th':
            $attributes = apply_filters('newsletter_campaign_html_attributes_th', array(
                array(
                    'name'  => 'nc-shortcode-arg-th-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-bgcolor',
                    'arg'   => 'bgcolor',
                    'title' => 'bgcolor'
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
                    'title' => 'nowrap'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-rowspan',
                    'arg'   => 'rowspan',
                    'title' => 'rowspan'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-th-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'td':
            $attributes = apply_filters('newsletter_campaign_html_attributes_td', array(
                array(
                    'name'  => 'nc-shortcode-arg-td-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-bgcolor',
                    'arg'   => 'bgcolor',
                    'title' => 'bgcolor'
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
                    'title' => 'nowrap'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-rowspan',
                    'arg'   => 'rowspan',
                    'title' => 'rowspan'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-td-width',
                    'arg'   => 'width',
                    'title' => 'width'
                )
            ));
            break;

        case 'colgroup':
            $attributes = apply_filters('newsletter_campaign_html_attributes_colgroup', array(
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-span',
                    'arg'   => 'span',
                    'title' => 'span'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-colgroup-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
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
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-col-span',
                    'arg'   => 'span',
                    'title' => 'span'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-col-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
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
                    'title' => 'align'
                )
            ));
            break;

        case 'thead':
            $attributes = apply_filters('newsletter_campaign_html_attributes_thead', array(
                array(
                    'name'  => 'nc-shortcode-arg-thead-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-thead-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
                )
            ));
            break;

        case 'tbody':
            $attributes = apply_filters('newsletter_campaign_html_attributes_tbody', array(
                array(
                    'name'  => 'nc-shortcode-arg-tbody-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tbody-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
                )
            ));
            break;

        case 'tfoot':
            $attributes = apply_filters('newsletter_campaign_html_attributes_tfoot', array(
                array(
                    'name'  => 'nc-shortcode-arg-tfoot-align',
                    'arg'   => 'align',
                    'title' => 'align'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tfoot-valign',
                    'arg'   => 'valign',
                    'title' => 'valign'
                )
            ));
            break;

        default: // Defaults to general attributes
            $attributes = apply_filters( 'newsletter_campaign_html_attributes_general', array(
                array(
                    'name'  => 'nc-shortcode-arg-accesskey',
                    'arg'   => 'accesskey',
                    'title' => 'Accesskey'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-class',
                    'arg'   => 'class',
                    'title' => 'Class'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-contenteditable',
                    'arg'   => 'contenteditable',
                    'title' => 'Contenteditable'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-dir',
                    'arg'   => 'dir',
                    'title' => 'Dir'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-hidden',
                    'arg'   => 'hidden',
                    'title' => 'Hidden'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-id',
                    'arg'   => 'id',
                    'title' => 'ID'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-lang',
                    'arg'   => 'lang',
                    'title' => 'Lang'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-style',
                    'arg'   => 'style',
                    'title' => 'Style'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-tabindex',
                    'arg'   => 'tabindex',
                    'title' => 'Tabindex'
                ),
                array(
                    'name'  => 'nc-shortcode-arg-title',
                    'arg'   => 'title',
                    'title' => 'Title'
                )
            ));
            break;
    }

    return $attributes;
}


function nc_general_html_attributes() {
    $general_attributes = apply_filters('newsletter_campaign_general_html_attributes', array(
        array(
            'name'  => 'nc-shortcode-arg-accesskey',
            'arg'   => 'accesskey',
            'title' => 'Accesskey'
        ),
        array(
            'name'  => 'nc-shortcode-arg-class',
            'arg'   => 'class',
            'title' => 'Class'
        ),
        array(
            'name'  => 'nc-shortcode-arg-contenteditable',
            'arg'   => 'contenteditable',
            'title' => 'Contenteditable'
        ),
        array(
            'name'  => 'nc-shortcode-arg-dir',
            'arg'   => 'dir',
            'title' => 'Dir'
        ),
        array(
            'name'  => 'nc-shortcode-arg-hidden',
            'arg'   => 'hidden',
            'title' => 'Hidden'
        ),
        array(
            'name'  => 'nc-shortcode-arg-id',
            'arg'   => 'id',
            'title' => 'ID'
        ),
        array(
            'name'  => 'nc-shortcode-arg-lang',
            'arg'   => 'lang',
            'title' => 'Lang'
        ),
        array(
            'name'  => 'nc-shortcode-arg-style',
            'arg'   => 'style',
            'title' => 'Style'
        ),
        array(
            'name'  => 'nc-shortcode-arg-tabindex',
            'arg'   => 'tabindex',
            'title' => 'Tabindex'
        ),
        array(
            'name'  => 'nc-shortcode-arg-title',
            'arg'   => 'title',
            'title' => 'Title'
        )
    ));

    return $general_attributes;
}