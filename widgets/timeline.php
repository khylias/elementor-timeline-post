<?php
namespace Timeline\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Timeline
 *
 * Elementor widget for timeline.
 *
 * @since 1.0.0
 */
class Timeline extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'timeline';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Timeline', 'timeline' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'timeline' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'timeline' ),
			]
		);

        $this->add_control(
            'post_type',
            [
                'label' => __( 'Post Type', 'timeline' ),
                'type' => Controls_Manager::SELECT,
                'options' => timeline_post_types(),
                'default' => 'post',

            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __( 'Category ID', 'elementor' ),
                'description' => __('Comma separated list of category ids','elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'post_type' => 'post'
                ]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', 'timeline' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'border-color',
            [
                'label' => __( 'Border color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '.timeline .timeline-border-color' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'circle-color',
            [
                'label' => __( 'Time Marker color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '.timeline .in-view.timeline-marker-color:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$post_args = timeline_post_settings($settings);
		$posts = timeline_post_data($post_args);
		?>
        <div class="timeline">
            <ul>
                <?php foreach($posts as $key => $post){
                    setup_postdata($post);
                ?>
                    <li class="timeline-marker-color">
                        <div class="timeline-border-color">
                            <time>
                                <?php
                                $custom_fields = get_post_custom($post->ID);
                                $my_custom_field = $custom_fields['date_timeline'];
                                foreach ( $my_custom_field as $key => $value ) {
                                    echo $value;
                                }
                                ?>
                            </time>
                            <?php the_content(); ?>
                        </div>
                    </li>
                <?php
                    }
                wp_reset_postdata();
                ?>
            </ul>
        </div>
        <?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="title">
			{{{ settings.title }}}
		</div>
		<?php
	}
}
