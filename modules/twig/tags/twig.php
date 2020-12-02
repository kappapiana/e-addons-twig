<?php

namespace EAddonsTwig\Modules\Twig\Tags;

//use Elementor\Core\DynamicTags\Tag;
use \Elementor\Controls_Manager;
use EAddonsForElementor\Core\Utils;
use Elementor\Modules\DynamicTags\Module;
use EAddonsForElementor\Base\Base_Tag;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Twig extends Base_Tag {

    static $acf_names = [];    

    public function get_name() {
        return 'e-tag-twig';
    }
    /*
    public function get_icon() {
        return 'eadd-dynamic-tag-twig';
    }
    
    public function get_pid() {
        return 208;
    }
    */
    public function get_title() {
        return __('Twig', 'e-addons');
    }

    /**
     * Register Controls
     *
     * Registers the Dynamic tag controls
     *
     * @since 2.0.0
     * @access protected
     *
     * @return void
     */
    protected function _register_controls() {

        $objects = array('post', 'user', 'term');

        $this->add_control(
                'e_twig_wizard',
                [
                    'label' => __('Wizard', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'default' => 'yes',
                ]
        );

        $this->add_control(
                'e_twig',
                [
                    'label' => __('Twig', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => '[post:title], [post:meta_key], [user:display_name], [term:name], [wp_query:posts]',
                    'condition' => [
                        'e_twig_wizard' => '',
                    ],
                ]
        );

        $obj_opt = [
            'post' => __('Post', 'e-addons'),
            'user' => __('User', 'e-addons'),
            'term' => __('Term', 'e-addons'),
            // 'comment' =>__('Comment', 'e-addons'),
            'site'  => __('Site (Option)', 'e-addons'),
            'posts' => __('Posts (Archive)', 'e-addons'),
            'wp_query' => __('WP Query', 'e-addons'),
            'query_object' => __('Query Object', 'e-addons'),
            'date' => __('Date', 'e-addons'),
            'system' => __('System', 'e-addons'),
        ];
        
        $this->add_control(
                'e_twig_object', [
            'label' => __('Object', 'e-addons'),
            'type' => Controls_Manager::SELECT,
            'options' => $obj_opt,
            'default' => 'post',
            'condition' => [
                'e_twig_wizard!' => '',
            ],
                ]
        );

        $this->add_control(
                'e_twig_field_date',
                [
                    'label' => __('Modifier', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => '+1 week, -2 mounths, yesterday, timestamp',
                    'description' => __('A time modificator compabile with strtotime OR a timestamp', 'e-addons'),
                    'condition' => [
                        'e_twig_wizard!' => '',
                        'e_twig_object' => 'date',
                    ],
                ]
        );
        $this->add_control(
                'e_twig_field_date_format',
                [
                    'label' => __('Format', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => 'Y-m-d H:i:s',
                    'label_block' => true,
                    'condition' => [
                        'e_twig_wizard!' => '',
                        'e_twig_object' => 'date',
                    ],
                ]
        );

        $this->add_control(
                'e_twig_field_system',
                [
                    'label' => __('Field', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => __('get, post, server, cookie', 'e-addons'),
                    'condition' => [
                        'e_twig_wizard!' => '',
                        'e_twig_object' => 'system',
                    ],
                ]
        );

        foreach ($objects as $aobj) {
            $this->add_control(
                    'e_twig_field_' . $aobj,
                    [
                        'label' => __('Field', 'e-addons'),
                        'type' => 'e-query',
                        'placeholder' => __('Meta key or Field Name', 'e-addons'),
                        'label_block' => true,
                        'query_type' => 'fields',
                        'object_type' => $aobj,
                        'condition' => [
                            'e_twig_wizard!' => '',
                            'e_twig_object' => $aobj,
                        ],
                    ]
            );
        }

        $this->add_control(
                'e_twig_field_site',
                [
                    'label' => __('Field', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Option key', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'options',
                    'condition' => [
                        'e_twig_wizard!' => '',
                        'e_twig_object' => 'site',
                    ],
                ]
        );

        $this->add_control(
                'e_twig_subfield',
                [
                    'label' => __('SubField', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => __('my_sub.label', 'e-addons'),
                    'condition' => [
                        'e_twig_wizard!' => '',
                        'e_twig_object!' => 'date',
                    ],
                ]
        );
        /* $this->add_control(
          'e_twig_source',
          [
          'label' => __('Source', 'e-addons'),
          'type' => \Elementor\Controls_Manager::TEXT,
          'label_block' => true,
          'condition' => [
          'e_twig_wizard!' => '',
          ],
          ]
          ); */
        /*foreach ($objects as $aobj) {
            $cond = $aobj;
            if (Utils::is_plugin_active('acf') && $aobj == 'post') {
                $cond = array('acf', $cond);
            }
            $this->add_control(
                    'e_twig_source_' . $aobj,
                    [
                        'label' => __('Source', 'e-addons'),
                        'type' => 'e-query',
                        'placeholder' => __('Search ' . ucfirst($aobj), 'e-addons'),
                        'label_block' => true,
                        'query_type' => $aobj . 's',
                        'condition' => [
                            'e_twig_wizard!' => '',
                            'e_twig_object' => $cond,
                        ],
                    ]
            );
        }*/
        
        /*$this->add_control(
                'e_twig_taxonomy',
                [
                    'label' => __('Taxonomy', 'e-addons'),
                    'type' => 'e-query',
                    'placeholder' => __('Taxonomy Name or slug', 'e-addons'),
                    'label_block' => true,
                    'query_type' => 'taxonomies',
                    'multiple' => true,
                    'condition' => [
                        'e_twig_wizard!' => '',
                        'e_twig_object' => 'term',
                    ],
                ]
        );*/

        $this->add_control(
                'e_twig_filter',
                [
                    'label' => __('Filters', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                    'rows' => 2,
                    'placeholder' => 'trim',
                    'label_block' => true,
                    'condition' => [
                        'e_twig_wizard!' => '',
                    ],
                ]
        );

        $this->add_control(
                'e_twig_code',
                [
                    'label' => __('Show code', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'condition' => [
                        'e_twig_wizard!' => '',
                    ],
                ]
        );

        $this->add_control(
                'e_twig_data',
                [
                    'label' => __('Return as Data', 'e-addons'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'description' => __('Required for MEDIA Controls and other Controls which need a structured data', 'e-addons'),
                    'condition' => [
                        'e_twig_code' => '',
                    ],
                ]
        );

        Utils::add_help_control($this);
    }

    public function render() {
        $settings = $this->get_settings();
        if (empty($settings))
            return;

        $value = $this->get_twig_value($settings);
        //var_dump($value); die();

        echo $value;
    }

    public function get_twig_value($settings) {
        //var_dump($settings);
        if (!empty($settings['e_twig_wizard'])) {
            $objects = array('post', 'user', 'term');
            
            $open = '{{';
            $twig = $open;
            $twig .= $settings['e_twig_object'];

            foreach ($objects as $aobj) {
                if ($settings['e_twig_object'] == $aobj 
                    && !empty($settings['e_twig_field_' . $aobj])) {
                    $twig .= '.' . $settings['e_twig_field_' . $aobj];
                }
            }

            if ($settings['e_twig_object'] == 'system' && $settings['e_twig_field_system']) {
                $twig .= '.' . $settings['e_twig_field_system'];
            }
            if ($settings['e_twig_field_site']) {
                $twig .= '.' . $settings['e_twig_field_site'];
            }

            if ($settings['e_twig_subfield']) {
                $twig .= '.' . $settings['e_twig_subfield'];
            }

            if ($settings['e_twig_object'] == 'date') {
                $twig = $open.'"now"';
                if ($settings['e_twig_field_date']) {
                    $twig .= '|date_modify("' . $settings['e_twig_field_date'].'")';
                }
                if ($settings['e_twig_field_date_format']) {
                    $twig .= '|date("' . $settings['e_twig_field_date_format'].'")';
                }
            }
            
            /*if ($settings['e_twig_object'] == 'term' && !empty($settings['e_twig_taxonomy'])) {
                $twig .= '|' . implode('|', $settings['e_twig_taxonomy']);
            }*/
            
            if ($settings['e_twig_filter']) {
                $filters = explode(PHP_EOL, $settings['e_twig_filter']);
                $twig .= '|' . implode('|', $filters);
            }

            $twig .= '}}';
            
            if ($settings['e_twig_code']) {
                echo $twig;
                return;
            }
        } else {
            $twig = $settings['e_twig'];
        }

        $value = Utils::get_dynamic_data($twig);
        return $value;
    }

    public function get_value(array $options = []) {
        $settings = $this->get_settings_for_display(null, true);
        if (empty($settings))
            return;

        \EAddonsTwigs\Modules\Twigs\Twigs::$data = true;
        $value = $this->get_twig_value($settings);
        \EAddonsTwigs\Modules\Twigs\Twigs::$data = false;

        // for MEDIA Control
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $image_data = [
                'url' => $value,
            ];
            $thumbnail_id = Utils::url_to_postid($value);
            if ($thumbnail_id) {
                $image_data['id'] = $thumbnail_id;
            }
            //var_dump($image_data);
            return $image_data;
        }

        return $value;
    }

    /**
     * @since 2.0.0
     * @access public
     *
     * @param array $options
     *
     * @return string
     */
    public function get_content(array $options = []) {
        $settings = $this->get_settings();

        if ($settings['e_twig_data']) {
            $value = $this->get_value($options);
        } else {
            ob_start();
            $this->render();
            $value = ob_get_clean();
        }

        if (empty($value)) {
            // TODO: fix spaces in `before`/`after` if WRAPPED_TAG ( conflicted with .elementor-tag { display: inline-flex; } );
            if (!\Elementor\Utils::is_empty($settings, 'before')) {
                $value = wp_kses_post($settings['before']) . $value;
            }

            if (!\Elementor\Utils::is_empty($settings, 'after')) {
                $value .= wp_kses_post($settings['after']);
            }
        } elseif (!\Elementor\Utils::is_empty($settings, 'fallback')) {
            $value = $settings['fallback'];
            $value = Utils::get_dynamic_data($value);
        }

        return $value;
    }

}