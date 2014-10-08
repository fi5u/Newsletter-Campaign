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

        case 'samp':
            $attributes = apply_filters('newsletter_campaign_html_attributes_samp', array());
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