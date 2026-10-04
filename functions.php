<?php
/**
 * BMKG Betoambari Theme Functions
 * Version: 1.2
 * Updated with YouTube Video Settings, Instagram Feed, and IKM Settings
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Setup
function bmkg_theme_setup() {
    // Theme supports
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    
    // Image sizes
    set_post_thumbnail_size(400, 300, true);
    add_image_size('news-thumbnail', 400, 250, true);
    add_image_size('bulletin-thumbnail', 300, 400, true);
    add_image_size('featured-large', 1200, 600, true);
    add_image_size('gallery-thumb', 350, 250, true);
    
    // Register menus
    register_nav_menus(array(
        'primary' => 'Menu Utama',
        'footer' => 'Menu Footer'
    ));
}
add_action('after_setup_theme', 'bmkg_theme_setup');

// Enqueue Styles and Scripts
function bmkg_enqueue_assets() {
    // Styles
    wp_enqueue_style('bmkg-style', get_stylesheet_uri(), array(), '2.0.0');
    wp_enqueue_style('bmkg-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap', array(), null);

    // Scripts
    wp_enqueue_script('bmkg-main', get_template_directory_uri() . '/assets/js/main.js', array(), '2.0.0', true);

    // Localize script for AJAX
    wp_localize_script('bmkg-main', 'bmkg_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bmkg_ajax_nonce'),
        'home_url' => home_url(),
        'theme_url' => get_template_directory_uri()
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'bmkg_enqueue_assets');

// ACF Options Pages
if (function_exists('acf_add_options_page')) {
    // Main Theme Settings
    acf_add_options_page(array(
        'page_title' => 'Pengaturan Tema BMKG',
        'menu_title' => 'Pengaturan Tema',
        'menu_slug' => 'bmkg-theme-settings',
        'capability' => 'edit_posts',
        'icon_url' => 'dashicons-cloud',
        'position' => 30
    ));
    
    // Sub pages
    acf_add_options_sub_page(array(
        'page_title' => 'Header & Footer',
        'menu_title' => 'Header & Footer',
        'parent_slug' => 'bmkg-theme-settings',
    ));
    
    acf_add_options_sub_page(array(
        'page_title' => 'Kontak & Media Sosial',
        'menu_title' => 'Kontak & Media Sosial',
        'parent_slug' => 'bmkg-theme-settings',
    ));
    
    acf_add_options_sub_page(array(
        'page_title' => 'Peringatan Cuaca',
        'menu_title' => 'Peringatan Cuaca',
        'parent_slug' => 'bmkg-theme-settings',
    ));
}

// AJAX Load More Posts
function bmkg_load_more_posts() {
    // Verify nonce
    check_ajax_referer('bmkg_ajax_nonce', 'nonce');
    
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $post_type_filter = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'berita';
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 6,
        'paged' => $page,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'post_type_select',
                'value' => $post_type_filter,
                'compare' => '='
            )
        )
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <article class="news-card" data-type="<?php echo $post_type_filter; ?>">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="news-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('news-thumbnail', ['alt' => get_the_title()]); ?>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="news-image">
                        <a href="<?php the_permalink(); ?>">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/default-news.jpg" alt="<?php the_title(); ?>">
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="news-content">
                    <div class="news-meta">
                        <i class="fas fa-calendar-alt"></i>
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date('j F Y'); ?>
                        </time>
                        <span style="margin-left: 15px; background: var(--<?php echo ($post_type_filter == 'artikel') ? 'green' : 'navy'; ?>); color: white; padding: 3px 10px; border-radius: 12px; font-size: 0.8rem;">
                            <?php echo ucfirst($post_type_filter); ?>
                        </span>
                    </div>
                    
                    <h3>
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h3>
                    
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    
                    <a href="<?php the_permalink(); ?>" class="read-more">
                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '';
    endif;
    
    wp_die();
}
add_action('wp_ajax_load_more_posts', 'bmkg_load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'bmkg_load_more_posts');

// AJAX Search
function bmkg_ajax_search() {
    check_ajax_referer('bmkg_ajax_nonce', 'nonce');
    
    $search_query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
    
    if (strlen($search_query) < 3) {
        wp_die();
    }
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        's' => $search_query,
        'post_status' => 'publish'
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) :
        echo '<div class="search-results-ajax">';
        echo '<h4>Hasil Pencarian untuk "' . esc_html($search_query) . '" (' . $query->found_posts . ' hasil)</h4>';
        echo '<div class="search-results-grid">';
        
        while ($query->have_posts()) : $query->the_post();
            ?>
            <div class="search-result-item">
                <a href="<?php the_permalink(); ?>">
                    <h5><?php the_title(); ?></h5>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                    <span class="search-meta">
                        <i class="fas fa-calendar"></i> <?php echo get_the_date('j F Y'); ?>
                    </span>
                </a>
            </div>
            <?php
        endwhile;
        
        echo '</div>';
        echo '</div>';
    else :
        echo '<p>Tidak ada hasil untuk "' . esc_html($search_query) . '"</p>';
    endif;
    
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_bmkg_search', 'bmkg_ajax_search');
add_action('wp_ajax_nopriv_bmkg_search', 'bmkg_ajax_search');

// Get default images
function bmkg_get_default_image($type) {
    $defaults = array(
        'logo' => 'Logo-BMKG-2010-2.png',
        'office' => 'fotoKantor.jpg',
        'gedung_bmkg' => 'gedunngbmkg.jpg',
        'bulletin_cover' => 'BULETIN-JUNI-2025_opt_001.jpg',
        'slide1' => 'slide1.jpg',
        'slide2' => 'slide2.jpg',
        'slide3' => 'slide3.jpg',
        'slide4' => 'slide4.jpg',
        'default_news' => 'default-news.jpg'
    );
    
    $filename = isset($defaults[$type]) ? $defaults[$type] : 'default.jpg';
    return get_template_directory_uri() . '/assets/images/' . $filename;
}

// Custom Post Meta Box
function bmkg_add_post_meta_boxes() {
    add_meta_box(
        'post_type_selection',
        'Jenis Konten',
        'bmkg_post_type_meta_box_callback',
        'post',
        'side',
        'high'
    );
    
    add_meta_box(
        'post_additional_settings',
        'Pengaturan Tambahan',
        'bmkg_additional_settings_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'bmkg_add_post_meta_boxes');

function bmkg_post_type_meta_box_callback($post) {
    wp_nonce_field('save_post_type_meta', 'post_type_meta_nonce');
    $value = get_post_meta($post->ID, 'post_type_select', true);
    $featured = get_post_meta($post->ID, 'is_featured', true);
    ?>
    <p>
        <label for="post_type_select">Pilih jenis konten:</label><br>
        <select name="post_type_select" id="post_type_select" style="width: 100%; margin-top: 5px;">
            <option value="berita" <?php selected($value, 'berita'); ?>>Berita</option>
            <option value="buletin" <?php selected($value, 'buletin'); ?>>Buletin</option>
            <option value="artikel" <?php selected($value, 'artikel'); ?>>Artikel</option>
        </select>
    </p>
    
    <p>
        <label>
            <input type="checkbox" name="is_featured" value="1" <?php checked($featured, '1'); ?>>
            Jadikan Featured Post
        </label>
    </p>
    <?php
}

function bmkg_additional_settings_callback($post) {
    $pdf_file = get_post_meta($post->ID, 'pdf_file_url', true);
    $external_link = get_post_meta($post->ID, 'external_link', true);
    ?>
    <p>
        <label for="pdf_file_url">URL File PDF (untuk Buletin):</label><br>
        <input type="url" name="pdf_file_url" id="pdf_file_url" value="<?php echo esc_url($pdf_file); ?>" style="width: 100%; margin-top: 5px;">
    </p>
    
    <p>
        <label for="external_link">Link Eksternal:</label><br>
        <input type="url" name="external_link" id="external_link" value="<?php echo esc_url($external_link); ?>" style="width: 100%; margin-top: 5px;">
    </p>
    <?php
}

function bmkg_save_post_meta($post_id) {
    // Check nonce
    if (!isset($_POST['post_type_meta_nonce']) || !wp_verify_nonce($_POST['post_type_meta_nonce'], 'save_post_type_meta')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save post type
    if (isset($_POST['post_type_select'])) {
        update_post_meta($post_id, 'post_type_select', sanitize_text_field($_POST['post_type_select']));
    }
    
    // Save featured status
    if (isset($_POST['is_featured'])) {
        update_post_meta($post_id, 'is_featured', '1');
    } else {
        delete_post_meta($post_id, 'is_featured');
    }
    
    // Save PDF URL
    if (isset($_POST['pdf_file_url'])) {
        update_post_meta($post_id, 'pdf_file_url', esc_url_raw($_POST['pdf_file_url']));
    }
    
    // Save external link
    if (isset($_POST['external_link'])) {
        update_post_meta($post_id, 'external_link', esc_url_raw($_POST['external_link']));
    }
}
add_action('save_post', 'bmkg_save_post_meta');

// Post Views Counter
function bmkg_set_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        $count = 0;
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

function bmkg_get_post_views($post_id) {
    $count_key = 'post_views_count';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        add_post_meta($post_id, $count_key, '0');
        return "0";
    }
    
    return $count;
}

// Track post views
function bmkg_track_post_views() {
    if (!is_single()) return;
    
    if (empty($_POST['post_id'])) {
        global $post;
        $post_id = $post->ID;
    } else {
        $post_id = $_POST['post_id'];
    }
    
    bmkg_set_post_views($post_id);
}
add_action('wp_head', 'bmkg_track_post_views');

// Custom excerpt
function bmkg_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'bmkg_excerpt_length');

function bmkg_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'bmkg_excerpt_more');

// Breadcrumbs
function bmkg_breadcrumbs() {
    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumbs" style="padding: 20px 0; background: white; border-bottom: 1px solid #eee;">';
        echo '<div class="container">';
        echo '<a href="' . home_url() . '" style="color: var(--navy); text-decoration: none;">Beranda</a>';
        
        if (is_category() || is_single()) {
            echo ' <span style="margin: 0 10px; color: #999;">›</span> ';
            
            if (is_single()) {
                $post_type = get_post_meta(get_the_ID(), 'post_type_select', true);
                if ($post_type == 'buletin') {
                    echo '<a href="' . home_url('/buletin') . '" style="color: var(--navy); text-decoration: none;">Buletin</a>';
                } else {
                    echo '<a href="' . home_url('/berita') . '" style="color: var(--navy); text-decoration: none;">Berita</a>';
                }
                echo ' <span style="margin: 0 10px; color: #999;">›</span> ';
                echo '<span style="color: #666;">' . wp_trim_words(get_the_title(), 5, '...') . '</span>';
            }
        } elseif (is_page()) {
            echo ' <span style="margin: 0 10px; color: #999;">›</span> ';
            echo '<span style="color: #666;">' . get_the_title() . '</span>';
        }
        
        echo '</div>';
        echo '</nav>';
    }
}

// Widget Areas
function bmkg_widgets_init() {
    register_sidebar(array(
        'name' => 'Sidebar',
        'id' => 'sidebar-1',
        'description' => 'Widget area untuk sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => 'Footer Widget 1',
        'id' => 'footer-1',
        'description' => 'Widget area untuk footer kolom 1',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
        'name' => 'Footer Widget 2',
        'id' => 'footer-2',
        'description' => 'Widget area untuk footer kolom 2',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
        'name' => 'Footer Widget 3',
        'id' => 'footer-3',
        'description' => 'Widget area untuk footer kolom 3',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="footer-widget-title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'bmkg_widgets_init');

// Theme activation
function bmkg_theme_activation() {
    // Create default categories
    $categories = array('Berita', 'Buletin', 'Artikel', 'Pengumuman');
    
    foreach ($categories as $cat_name) {
        if (!term_exists($cat_name, 'category')) {
            wp_insert_term($cat_name, 'category', array(
                'slug' => sanitize_title($cat_name)
            ));
        }
    }
    
    // Create default pages
    $pages = array(
        'Berita'          => 'page-berita.php',
        'Buletin'         => 'page-buletin.php',
        'Profil BMKG'     => 'page-profil-bmkg.php',
        'Profil Stasiun'  => 'page-profil-stasiun.php',
        'Daftar Pegawai'  => 'page-daftar-pegawai.php',
        'IKM'             => 'page-ikm.php',
        'Kontak'          => 'page-kontak.php',
        'Layanan Data'    => 'page-layanan-data.php',
    );
    
    foreach ($pages as $title => $template) {
        $slug = sanitize_title($title);
        $existing = get_posts(array(
            'post_type'   => 'page',
            'name'        => $slug,
            'numberposts' => 1,
        ));
        if (empty($existing)) {
            $page_id = wp_insert_post(array(
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ));
            
            if ($page_id && $template) {
                update_post_meta($page_id, '_wp_page_template', $template);
            }
        }
    }
    
    // Set homepage
    $home_check = get_posts(array(
        'post_type'   => 'page',
        'name'        => 'beranda',
        'numberposts' => 1,
    ));
    if (empty($home_check)) {
        $homepage_id = wp_insert_post(array(
            'post_title'   => 'Beranda',
            'post_name'    => 'beranda',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'page'
        ));
        update_option('page_on_front', $homepage_id);
        update_option('show_on_front', 'page');
    }
}
add_action('after_switch_theme', 'bmkg_theme_activation');

// Dashboard widgets
function bmkg_dashboard_widgets() {
    // Remove default widgets
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    
    // Add custom widget
    wp_add_dashboard_widget(
        'bmkg_dashboard_widget',
        'BMKG Betoambari Dashboard',
        'bmkg_dashboard_widget_content'
    );
    
    // Add statistics widget
    wp_add_dashboard_widget(
        'bmkg_stats_widget',
        'Statistik Website',
        'bmkg_stats_widget_content'
    );
}
add_action('wp_dashboard_setup', 'bmkg_dashboard_widgets');

function bmkg_dashboard_widget_content() {
    echo '<div style="text-align: center; padding: 20px;">';
    echo '<img src="' . get_template_directory_uri() . '/assets/images/Logo-BMKG-2010-2.png" style="height: 80px; margin-bottom: 20px;">';
    echo '<h3 style="color: #0a2351; margin-bottom: 15px;">Selamat datang di BMKG Betoambari!</h3>';
    echo '<p>Tema berhasil diaktivasi dan siap digunakan.</p>';
    echo '<div style="margin-top: 20px; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">';
    echo '<a href="' . admin_url('post-new.php') . '" class="button button-primary">Buat Post Baru</a>';
    echo '<a href="' . admin_url('admin.php?page=bmkg-theme-settings') . '" class="button">Pengaturan Tema</a>';
    echo '<a href="' . admin_url('admin.php?page=bmkg-video-settings') . '" class="button">Video Settings</a>';
    echo '<a href="' . home_url() . '" class="button" target="_blank">Lihat Website</a>';
    echo '</div>';
    echo '</div>';
}

function bmkg_stats_widget_content() {
    $count_posts = wp_count_posts();
    $count_pages = wp_count_posts('page');
    
    // Count by post type
    $berita_count = count(get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_key' => 'post_type_select',
        'meta_value' => 'berita'
    )));
    
    $buletin_count = count(get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_key' => 'post_type_select',
        'meta_value' => 'buletin'
    )));
    
    echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">';
    
    echo '<div style="background: #f0f8ff; padding: 15px; border-radius: 8px; text-align: center;">';
    echo '<div style="font-size: 24px; font-weight: bold; color: #0a2351;">' . $count_posts->publish . '</div>';
    echo '<div style="font-size: 12px; color: #666;">Total Post</div>';
    echo '</div>';
    
    echo '<div style="background: #f0fff0; padding: 15px; border-radius: 8px; text-align: center;">';
    echo '<div style="font-size: 24px; font-weight: bold; color: #27ae60;">' . $count_pages->publish . '</div>';
    echo '<div style="font-size: 12px; color: #666;">Total Halaman</div>';
    echo '</div>';
    
    echo '<div style="background: #fff8dc; padding: 15px; border-radius: 8px; text-align: center;">';
    echo '<div style="font-size: 24px; font-weight: bold; color: #f39c12;">' . $berita_count . '</div>';
    echo '<div style="font-size: 12px; color: #666;">Berita</div>';
    echo '</div>';
    
    echo '<div style="background: #ffefd5; padding: 15px; border-radius: 8px; text-align: center;">';
    echo '<div style="font-size: 24px; font-weight: bold; color: #e67e22;">' . $buletin_count . '</div>';
    echo '<div style="font-size: 12px; color: #666;">Buletin</div>';
    echo '</div>';
    
    echo '</div>';
}

// Clean up WordPress
function bmkg_cleanup() {
    // Remove unnecessary headers
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove jQuery Migrate
    add_action('wp_default_scripts', function($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            if ($script->deps) {
                $script->deps = array_diff($script->deps, array('jquery-migrate'));
            }
        }
    });
}
add_action('init', 'bmkg_cleanup');

// Security Headers
function bmkg_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: no-referrer-when-downgrade');
}
add_action('send_headers', 'bmkg_security_headers');

// Custom login logo
function bmkg_login_logo() {
    ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/assets/images/Logo-BMKG-2010-2.png);
            height: 100px;
            width: 320px;
            background-size: contain;
            background-repeat: no-repeat;
            padding-bottom: 10px;
        }
        body.login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login form {
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .login #backtoblog a, .login #nav a {
            color: white !important;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'bmkg_login_logo');

// Add theme support for editor styles
function bmkg_add_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('style-editor.css');
}
add_action('after_setup_theme', 'bmkg_add_editor_styles');

// Disable automatic updates for this theme
add_filter('auto_update_theme', function($update, $item) {
    if ( isset( $item->slug ) && $item->slug === 'bmkg-betoambari' ) {
        return false;
    }
    return $update;
}, 10, 2);

// Theme update notification
function bmkg_check_theme_version() {
    $theme_data = wp_get_theme();
    $current_version = $theme_data->get('Version');
    
    if (version_compare($current_version, '1.2', '<')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p><strong>BMKG Theme:</strong> Versi baru tersedia. Silakan update tema Anda.</p>';
            echo '</div>';
        });
    }
}
add_action('admin_init', 'bmkg_check_theme_version');

// =====================================================
// BMKG YOUTUBE VIDEO SETTINGS - FITUR BARU
// =====================================================

// Tambah menu BMKG Video Settings di WordPress Admin
add_action('admin_menu', 'bmkg_video_add_admin_menu');
function bmkg_video_add_admin_menu() {
    add_menu_page(
        'BMKG Video Settings',     // Judul halaman
        'BMKG Video',              // Nama menu di sidebar
        'manage_options',          // Capability
        'bmkg-video-settings',     // Menu slug
        'bmkg_video_settings_page', // Function callback
        'dashicons-video-alt3',    // Icon video
        31                         // Posisi menu
    );
}

// Register setting untuk YouTube URL
add_action('admin_init', 'bmkg_video_settings_init');
function bmkg_video_settings_init() {
    // Register setting
    register_setting('bmkg_video_settings', 'bmkg_youtube_prakiraan_url');
    
    // Add settings section
    add_settings_section(
        'bmkg_video_section',
        'Video Prakiraan Cuaca Mingguan',
        'bmkg_video_section_callback',
        'bmkg-video-settings'
    );
    
    // Add settings field
    add_settings_field(
        'bmkg_youtube_prakiraan_url',
        'YouTube Video URL',
        'bmkg_youtube_url_render',
        'bmkg-video-settings',
        'bmkg_video_section'
    );
}

// Section description callback
function bmkg_video_section_callback() {
    echo '<p>Masukkan URL video YouTube prakiraan cuaca mingguan Kota Baubau. Video akan tampil di homepage antara section Satelit/Radar dan Berita.</p>';
}

// Render input field untuk YouTube URL
function bmkg_youtube_url_render() {
    $value = get_option('bmkg_youtube_prakiraan_url');
    ?>
    <input type="url" 
           name="bmkg_youtube_prakiraan_url" 
           value="<?php echo esc_attr($value); ?>" 
           style="width: 500px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
           placeholder="https://www.youtube.com/watch?v=VIDEO_ID atau https://youtu.be/VIDEO_ID">
    
    <p class="description" style="margin-top: 10px; color: #666;">
        <strong>Format URL yang diterima:</strong><br>
        • https://www.youtube.com/watch?v=xxxxx<br>
        • https://youtu.be/xxxxx<br>
        • https://www.youtube.com/embed/xxxxx
    </p>
    
    <?php if ($value) : ?>
        <div style="margin-top: 15px; padding: 15px; background: #e7f5e7; border-left: 4px solid #27ae60; border-radius: 4px;">
            <strong style="color: #27ae60;">✓ Video sudah diset!</strong><br>
            <span style="color: #666;">URL Saat ini: <?php echo esc_html($value); ?></span><br>
            <small>Video akan tampil di homepage. <a href="<?php echo home_url(); ?>" target="_blank">Lihat Homepage →</a></small>
        </div>
    <?php else : ?>
        <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
            <strong style="color: #856404;">⚠ Video belum diset</strong><br>
            <span style="color: #666;">Paste URL video YouTube untuk menampilkan prakiraan cuaca di homepage.</span>
        </div>
    <?php endif; ?>
    <?php
}

// Halaman settings lengkap
function bmkg_video_settings_page() {
    ?>
    <div class="wrap">
        <h1 style="margin-bottom: 20px;">
            <span class="dashicons dashicons-video-alt3" style="font-size: 30px; margin-right: 10px; vertical-align: middle;"></span>
            BMKG Video Settings
        </h1>
        
        <?php settings_errors(); ?>
        
        <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="options.php" method="post">
                <?php
                settings_fields('bmkg_video_settings');
                do_settings_sections('bmkg-video-settings');
                submit_button('Simpan Video URL', 'primary', 'submit', true, array('style' => 'margin-top: 20px;'));
                ?>
            </form>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #f0f8ff; border-left: 4px solid #0073aa; border-radius: 4px;">
            <h3 style="margin-top: 0; color: #0073aa;">📋 Panduan Update Video Mingguan</h3>
            <ol style="line-height: 1.8;">
                <li><strong>Upload video</strong> ke YouTube channel BMKG Betoambari</li>
                <li><strong>Copy URL video</strong> dari address bar browser atau tombol Share</li>
                <li><strong>Paste URL</strong> di field di atas</li>
                <li>Klik tombol <strong>"Simpan Video URL"</strong></li>
                <li>Video akan <strong>otomatis muncul</strong> di homepage</li>
            </ol>
            
            <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-radius: 4px;">
                <strong style="color: #856404;">💡 Tips:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li>Update video setiap <strong>Senin pagi</strong></li>
                    <li>Durasi ideal: <strong>3-5 menit</strong></li>
                    <li>Judul format: "Prakiraan Cuaca Baubau [Tanggal]"</li>
                    <li>Video muncul di homepage antara Satelit/Radar dan Berita</li>
                    <li>Untuk hapus video, kosongkan field URL dan simpan</li>
                </ul>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px; text-align: center;">
            <a href="<?php echo home_url(); ?>" target="_blank" class="button button-secondary">
                <span class="dashicons dashicons-external" style="vertical-align: middle;"></span>
                Lihat Homepage
            </a>
            <a href="https://www.youtube.com/@bmkgbetoambari" target="_blank" class="button button-secondary" style="margin-left: 10px;">
                <span class="dashicons dashicons-youtube" style="vertical-align: middle;"></span>
                YouTube Channel BMKG Betoambari
            </a>
            <a href="<?php echo admin_url('admin.php?page=bmkg-theme-settings'); ?>" class="button button-secondary" style="margin-left: 10px;">
                <span class="dashicons dashicons-admin-settings" style="vertical-align: middle;"></span>
                Pengaturan Tema
            </a>
        </div>
    </div>
    <?php
}

// Success message setelah save
add_action('admin_notices', 'bmkg_video_admin_notices');
function bmkg_video_admin_notices() {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page'] == 'bmkg-video-settings') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>✓ Video URL berhasil disimpan!</strong> Video prakiraan cuaca sudah tampil di homepage. <a href="<?php echo home_url(); ?>" target="_blank">Lihat Homepage →</a></p>
        </div>
        <?php
    }
}

// END OF BMKG YOUTUBE VIDEO SETTINGS
// =====================================================

// =====================================================
// BMKG INSTAGRAM FEED SETTINGS
// =====================================================

// Tambah submenu Instagram di bawah BMKG Video
add_action('admin_menu', 'bmkg_instagram_add_admin_menu');
function bmkg_instagram_add_admin_menu() {
    add_submenu_page(
        'bmkg-video-settings',     // Parent slug
        'Instagram Feed Settings',  // Page title
        'Instagram Feed',           // Menu title
        'manage_options',           // Capability
        'bmkg-instagram-settings',  // Menu slug
        'bmkg_instagram_settings_page' // Callback function
    );
}

// Register settings untuk Instagram
add_action('admin_init', 'bmkg_instagram_settings_init');
function bmkg_instagram_settings_init() {
    // Register settings untuk 15 gambar Instagram
    for ($i = 1; $i <= 15; $i++) {
        register_setting('bmkg_instagram_settings', 'bmkg_instagram_image_' . $i);
        register_setting('bmkg_instagram_settings', 'bmkg_instagram_caption_' . $i);
        register_setting('bmkg_instagram_settings', 'bmkg_instagram_link_' . $i);
    }
    
    // Setting untuk auto-slide speed
    register_setting('bmkg_instagram_settings', 'bmkg_instagram_slide_speed');
}

// Halaman Instagram Settings
function bmkg_instagram_settings_page() {
    ?>
    <div class="wrap">
        <h1 style="margin-bottom: 20px;">
            <span class="dashicons dashicons-instagram" style="font-size: 30px; margin-right: 10px; vertical-align: middle;"></span>
            Prakiraan Cuaca Kota Baubau
        </h1>
        
        <div style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; border-radius: 4px; margin-bottom: 20px;">
            <strong>📸 Cara Pakai:</strong> Upload gambar prakiraan cuaca (screenshot atau download), maksimal 15 gambar. Gambar akan auto-slide di homepage.
        </div>
        
        <form action="options.php" method="post">
            <?php settings_fields('bmkg_instagram_settings'); ?>
            
            <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                
                <!-- Slide Speed Setting -->
                <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <h3>Pengaturan Slide</h3>
                    <label>
                        Kecepatan Auto-Slide (detik):
                        <input type="number" 
                               name="bmkg_instagram_slide_speed" 
                               value="<?php echo get_option('bmkg_instagram_slide_speed', 3); ?>" 
                               min="1" 
                               max="10" 
                               style="width: 80px; margin-left: 10px;">
                    </label>
                    <p class="description">Default: 3 detik per slide</p>
                </div>
                
                <!-- Prakiraan Cuaca Images -->
                <h3>Upload Gambar Prakiraan Cuaca (Max 15)</h3>
                
                <?php for ($i = 1; $i <= 15; $i++) : 
                    $image_url = get_option('bmkg_instagram_image_' . $i);
                    $caption = get_option('bmkg_instagram_caption_' . $i);
                    $link = get_option('bmkg_instagram_link_' . $i);
                ?>
                    <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                        <h4 style="margin-top: 0; color: #495057;">
                            <span style="background: #0073aa; color: white; padding: 2px 10px; border-radius: 15px; font-size: 14px;">
                                Prakiraan #<?php echo $i; ?>
                            </span>
                        </h4>
                        
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 20px; align-items: start;">
                            <!-- Preview -->
                            <div>
                                <?php if ($image_url) : ?>
                                    <img src="<?php echo esc_url($image_url); ?>" 
                                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 2px solid #ddd;">
                                <?php else : ?>
                                    <div style="width: 150px; height: 150px; background: #e9ecef; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                                        <span>No Image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Input Fields -->
                            <div>
                                <div style="margin-bottom: 10px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">URL Gambar:</label>
                                    <input type="url" 
                                           name="bmkg_instagram_image_<?php echo $i; ?>" 
                                           value="<?php echo esc_url($image_url); ?>" 
                                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                                           placeholder="https://example.com/image.jpg">
                                    <button type="button" 
                                            onclick="uploadImage(<?php echo $i; ?>)" 
                                            class="button button-secondary" 
                                            style="margin-top: 5px;">
                                        Upload dari Media Library
                                    </button>
                                </div>
                                
                                <div style="margin-bottom: 10px;">
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Caption (Optional):</label>
                                    <input type="text" 
                                           name="bmkg_instagram_caption_<?php echo $i; ?>" 
                                           value="<?php echo esc_attr($caption); ?>" 
                                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                                           placeholder="Caption untuk gambar prakiraan ini">
                                </div>
                                
                                <div>
                                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Link Sumber (Optional):</label>
                                    <input type="url" 
                                           name="bmkg_instagram_link_<?php echo $i; ?>" 
                                           value="<?php echo esc_url($link); ?>" 
                                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                                           placeholder="https://bmkg.go.id">
                                </div>
                                
                                <?php if ($image_url) : ?>
                                    <button type="button" 
                                            onclick="clearImage(<?php echo $i; ?>)" 
                                            class="button" 
                                            style="margin-top: 10px; background: #dc3545; color: white; border-color: #dc3545;">
                                        Hapus Gambar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
                
                <?php submit_button('Simpan Semua Perubahan', 'primary', 'submit', true, array('style' => 'margin-top: 20px;')); ?>
            </div>
        </form>
        
        <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px; text-align: center;">
            <a href="<?php echo home_url(); ?>#prakiraan-cuaca-section" target="_blank" class="button button-secondary">
                <span class="dashicons dashicons-external" style="vertical-align: middle;"></span>
                Lihat di Homepage
            </a>
        </div>
    </div>
    
    <script>
    function uploadImage(number) {
        var frame = wp.media({
            title: 'Pilih Gambar Prakiraan Cuaca #' + number,
            button: { text: 'Gunakan Gambar Ini' },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            document.querySelector('input[name="bmkg_instagram_image_' + number + '"]').value = attachment.url;
        });
        
        frame.open();
    }
    
    function clearImage(number) {
        if (confirm('Hapus gambar ini?')) {
            document.querySelector('input[name="bmkg_instagram_image_' + number + '"]').value = '';
            document.querySelector('input[name="bmkg_instagram_caption_' + number + '"]').value = '';
            document.querySelector('input[name="bmkg_instagram_link_' + number + '"]').value = '';
            document.querySelector('form').submit();
        }
    }
    </script>
    <?php
}

// Enqueue media uploader script di admin
add_action('admin_enqueue_scripts', 'bmkg_admin_scripts');
function bmkg_admin_scripts($hook) {
    if ($hook == 'bmkg-video_page_bmkg-instagram-settings' || $hook == 'bmkg-video_page_bmkg-ikm-settings') {
        wp_enqueue_media();
    }
}

// END OF BMKG INSTAGRAM FEED SETTINGS (NOW PRAKIRAAN CUACA)
// =====================================================
// =====================================================
// BMKG IKM SETTINGS - FITUR BARU
// =====================================================

// Tambah submenu IKM di bawah BMKG Video
add_action('admin_menu', 'bmkg_ikm_add_admin_menu');
function bmkg_ikm_add_admin_menu() {
    add_submenu_page(
        'bmkg-video-settings',     // Parent slug
        'IKM Settings',            // Page title
        'IKM Settings',            // Menu title
        'manage_options',          // Capability
        'bmkg-ikm-settings',       // Menu slug
        'bmkg_ikm_settings_page'   // Callback function
    );
}

// Register settings untuk IKM
add_action('admin_init', 'bmkg_ikm_settings_init');
function bmkg_ikm_settings_init() {
    // Register settings untuk IKM URL dan poster image
    register_setting('bmkg_ikm_settings', 'bmkg_ikm_form_url');
    register_setting('bmkg_ikm_settings', 'bmkg_ikm_poster_image');
    register_setting('bmkg_ikm_settings', 'bmkg_ikm_poster_title');
    register_setting('bmkg_ikm_settings', 'bmkg_ikm_poster_description');
}

// Halaman IKM Settings
function bmkg_ikm_settings_page() {
    ?>
    <div class="wrap">
        <h1 style="margin-bottom: 20px;">
            <span class="dashicons dashicons-chart-bar" style="font-size: 30px; margin-right: 10px; vertical-align: middle;"></span>
            IKM Settings
        </h1>
        
        <div style="background: #e3f2fd; padding: 15px; border-left: 4px solid #2196f3; border-radius: 4px; margin-bottom: 20px;">
            <strong>📊 Indeks Kepuasan Masyarakat:</strong> Atur URL form IKM dan poster/gambar yang akan ditampilkan di homepage.
        </div>
        
        <form action="options.php" method="post">
            <?php settings_fields('bmkg_ikm_settings'); ?>
            
            <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                
                <!-- IKM Form URL Setting -->
                <div style="margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <h3>URL Form IKM (Google Form)</h3>
                    
                    <?php 
                    // REVISI: Tambah validasi otomatis untuk tambah ?embedded=true kalau belum ada, dan cek kalau URL BMKG
                    $ikm_url = get_option('bmkg_ikm_form_url', 'https://docs.google.com/forms/d/e/1FAIpQLScX5gejgX-zVpsz9QJzYLMC9UPgYo-sIjqz1CPvrJ95S5FnaA/viewform?embedded=true');
                    if ($ikm_url && strpos($ikm_url, 'eskmbmk.go.id') !== false && strpos($ikm_url, '?embedded=true') === false) {
                        $ikm_url .= '?embedded=true'; // Otomatis tambah untuk BMKG kalau mirip Google Form
                        update_option('bmkg_ikm_form_url', $ikm_url);
                    } elseif ($ikm_url && strpos($ikm_url, 'docs.google.com') !== false && strpos($ikm_url, '?embedded=true') === false) {
                        $ikm_url .= '?embedded=true'; // Otomatis tambah untuk Google Form standar
                        update_option('bmkg_ikm_form_url', $ikm_url);
                    }
                    ?>
                    
                    <input type="url" 
                           name="bmkg_ikm_form_url" 
                           value="<?php echo esc_attr($ikm_url); ?>" 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 10px;"
                           placeholder="https://docs.google.com/forms/d/e/xxxxx/viewform?embedded=true">
                    
                    <p class="description">
                        <strong>Cara mendapat URL:</strong><br>
                        1. Buka Google Form IKM → Klik "Send" → Tab "Embed" → Copy URL dari iframe src<br>
                        2. Pastikan URL berakhir dengan "?embedded=true"<br>
                        // REVISI: Tambah catatan untuk BMKG
                        <strong>Catatan untuk link BMKG (eskmbmk.go.id):</strong> Kalau gak muncul, buat salinan form di Google Forms baru (publik), lalu pakai URL embed-nya. Link BMKG mungkin restricted.
                    </p>
                    
                    <?php if ($ikm_url) : ?>
                        <div style="margin-top: 15px; padding: 15px; background: #e7f5e7; border-left: 4px solid #4caf50; border-radius: 4px;">
                            <strong style="color: #4caf50;">✓ Form IKM aktif!</strong><br>
                            <span style="color: #666;">URL saat ini: <?php echo esc_html($ikm_url); ?></span>
                        </div>
                    <?php else: ?>
                        // REVISI: Tambah warning kalau URL kosong atau invalid
                        <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                            <strong style="color: #856404;">⚠️ Form IKM belum aktif!</strong> Masukkan URL valid untuk embed.
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- IKM Poster Image Setting -->
                <div style="margin-bottom: 30px;">
                    <h3>Poster/Gambar IKM</h3>
                    <p style="color: #666; margin-bottom: 15px;">Upload poster atau infografis terkait IKM yang akan ditampilkan sebelum form IKM.</p>
                    
                    <?php 
                    $poster_image = get_option('bmkg_ikm_poster_image');
                    $poster_title = get_option('bmkg_ikm_poster_title', 'Indeks Kepuasan Pelanggan');
                    $poster_description = get_option('bmkg_ikm_poster_description', 'Hasil survei kepuasan masyarakat terhadap pelayanan BMKG Betoambari');
                    ?>
                    
                    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 20px; align-items: start;">
                        <!-- Preview -->
                        <div>
                            <?php if ($poster_image) : ?>
                                <img src="<?php echo esc_url($poster_image); ?>" 
                                     style="width: 300px; height: 200px; object-fit: cover; border-radius: 10px; border: 2px solid #ddd;">
                            <?php else : ?>
                                <div style="width: 300px; height: 200px; background: #f5f5f5; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #999; border: 2px dashed #ddd;">
                                    <span>No Image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Input Fields -->
                        <div>
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">URL Gambar Poster:</label>
                                <input type="url" 
                                       name="bmkg_ikm_poster_image" 
                                       value="<?php echo esc_url($poster_image); ?>" 
                                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                                       placeholder="https://example.com/poster.jpg">
                                <button type="button" 
                                        onclick="uploadPosterImage()" 
                                        class="button button-secondary" 
                                        style="margin-top: 5px;">
                                    Upload dari Media Library
                                </button>
                            </div>
                            
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Judul Poster (Optional):</label>
                                <input type="text" 
                                       name="bmkg_ikm_poster_title" 
                                       value="<?php echo esc_attr($poster_title); ?>" 
                                       style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;"
                                       placeholder="Judul untuk poster">
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Deskripsi Poster (Optional):</label>
                                <textarea name="bmkg_ikm_poster_description" 
                                          style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; height: 80px;"
                                          placeholder="Deskripsi singkat tentang poster"><?php echo esc_textarea($poster_description); ?></textarea>
                            </div>
                            
                            <?php if ($poster_image) : ?>
                                <button type="button" 
                                        onclick="clearPosterImage()" 
                                        class="button" 
                                        style="margin-top: 10px; background: #dc3545; color: white; border-color: #dc3545;">
                                    Hapus Poster
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php submit_button('Simpan Pengaturan IKM', 'primary', 'submit', true, array('style' => 'margin-top: 20px;')); ?>
            </div>
        </form>
        
        <div style="margin-top: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px; text-align: center;">
            <a href="<?php echo home_url(); ?>#ikm-section" target="_blank" class="button button-secondary">
                <span class="dashicons dashicons-external" style="vertical-align: middle;"></span>
                Lihat di Homepage
            </a>
        </div>
    </div>
    
    <script>
    // REVISI: Tambah validasi JS sebelum submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        var ikmUrl = document.querySelector('input[name="bmkg_ikm_form_url"]').value;
        if (ikmUrl && !ikmUrl.includes('?embedded=true')) {
            if (confirm('URL belum punya "?embedded=true". Mau tambah otomatis?')) {
                document.querySelector('input[name="bmkg_ikm_form_url"]').value = ikmUrl + '?embedded=true';
            } else {
                e.preventDefault();
            }
        }
    });

    function uploadPosterImage() {
        var frame = wp.media({
            title: 'Pilih Poster IKM',
            button: { text: 'Gunakan Gambar Ini' },
            multiple: false
        });
        
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            document.querySelector('input[name="bmkg_ikm_poster_image"]').value = attachment.url;
        });
        
        frame.open();
    }
    
    function clearPosterImage() {
        if (confirm('Hapus poster ini?')) {
            document.querySelector('input[name="bmkg_ikm_poster_image"]').value = '';
            document.querySelector('input[name="bmkg_ikm_poster_title"]').value = '';
            document.querySelector('textarea[name="bmkg_ikm_poster_description"]').value = '';
            document.querySelector('form').submit();
        }
    }
    </script>
    <?php
}

// Success message setelah save
add_action('admin_notices', 'bmkg_ikm_admin_notices');
function bmkg_ikm_admin_notices() {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page'] == 'bmkg-ikm-settings') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>✓ Pengaturan IKM berhasil disimpan!</strong> Perubahan sudah tampil di homepage. <a href="<?php echo home_url(); ?>#ikm-section" target="_blank">Lihat Homepage →</a></p>
        </div>
        <?php
    }
}

// REVISI: Tambah shortcode baru untuk render form di frontend (homepage)
add_shortcode('bmkg_ikm_form', 'bmkg_ikm_render_form');
function bmkg_ikm_render_form() {
    $ikm_url = get_option('bmkg_ikm_form_url');
    $poster_image = get_option('bmkg_ikm_poster_image');
    $poster_title = get_option('bmkg_ikm_poster_title');
    $poster_description = get_option('bmkg_ikm_poster_description');

    ob_start();
    ?>
    <div id="ikm-section" style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
        <h2>Indeks Kepuasan Masyarakat (IKM)</h2>
        <p>Pendapat Anda sangat berarti bagi kami..</p>
        <?php if ($poster_image) : ?>
            <div style="margin-bottom: 20px; text-align: center;">
                <img src="<?php echo esc_url($poster_image); ?>" alt="<?php echo esc_attr($poster_title); ?>" style="max-width: 100%; height: auto; border-radius: 10px;">
                <h3><?php echo esc_html($poster_title); ?></h3>
                <p><?php echo esc_html($poster_description); ?></p>
            </div>
        <?php endif; ?>
        <?php if ($ikm_url) : ?>
            <iframe src="<?php echo esc_url($ikm_url); ?>" width="100%" height="800" frameborder="0" marginheight="0" marginwidth="0" style="border: none;">Loading...</iframe>
        <?php else : ?>
            <p style="color: #dc3545; text-align: center;">Form IKM belum diatur. Hubungi admin.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

// END OF BMKG IKM SETTINGS
// =====================================================

// =====================================================
// AJAX HANDLER UNTUK PERINGATAN DINI AUTO-UPDATE
// =====================================================

// AJAX handler untuk fetch peringatan dini
add_action('wp_ajax_get_peringatan_dini', 'bmkg_ajax_get_peringatan_dini');
add_action('wp_ajax_nopriv_get_peringatan_dini', 'bmkg_ajax_get_peringatan_dini');

function bmkg_ajax_get_peringatan_dini() {
    $peringatan_found = false;
    $peringatan_text = '';
    
    // Gunakan cURL untuk fetch halaman BMKG
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.bmkg.go.id/cuaca/peringatan-dini-cuaca/74");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $html_content = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code == 200 && $html_content) {
        // Check apakah ada mention "Kota Baubau"
        if (stripos($html_content, 'Kota Baubau') !== false) {
            // Extract teks peringatan yang relevan
            // Coba beberapa pattern untuk menangkap peringatan
            
            // Pattern 1: Cari paragraf yang mengandung Kota Baubau
            preg_match_all('/<p[^>]*>([^<]*Kota Baubau[^<]*)<\/p>/i', $html_content, $matches);
            
            if (!empty($matches[1])) {
                $peringatan_found = true;
                $peringatan_text = implode(' ', $matches[1]);
            } else {
                // Pattern 2: Cari dalam text yang sudah di-strip tags
                $clean_text = strip_tags($html_content);
                preg_match('/(?:UPDATE|WASPADA|Peringatan|Potensi)[^.]*Kota Baubau[^.]*\./i', $clean_text, $match);
                
                if (!empty($match)) {
                    $peringatan_found = true;
                    $peringatan_text = $match[0];
                }
            }
        }
    }
    
    // Prepare response
    $response = array();
    
    if ($peringatan_found && !empty($peringatan_text)) {
        // Highlight "Kota Baubau" dalam teks
        $highlighted_text = preg_replace(
            '/(Kota Baubau|Baubau)/i',
            '<strong style="background: #ffeb3b; padding: 2px 5px; border-radius: 3px;">$1</strong>',
            $peringatan_text
        );
        
        $response['status'] = 'found';
        $response['content'] = $highlighted_text;
        $response['timestamp'] = current_time('H:i:s');
    } else {
        $response['status'] = 'none';
        $response['timestamp'] = current_time('H:i:s');
    }
    
    // Send JSON response
    wp_send_json($response);
}

// END OF AJAX HANDLER
// =====================================================



/* ==== [BEGIN] CPT PEGAWAI (auto-inserted) ==== */
/**
 * ============================================================================
 *  CUSTOM POST TYPE: PEGAWAI  (BMKG Betoambari)
 * ============================================================================
 *
 *  CARA PASANG:
 *  Salin SELURUH isi file ini (TANPA baris "<?php" di paling atas)
 *  lalu tempel ke dalam functions.php milik Anda, TEPAT SEBELUM
 *  tag penutup "?>" di baris paling bawah.
 *
 *  Setelah terpasang, di WP Admin akan muncul menu baru "Pegawai".
 *  Admin cukup mengisi: Judul (= Nama Pegawai), Foto (Featured Image),
 *  Jabatan, NIP, dan Urutan Tampil. Halaman Daftar Pegawai akan otomatis
 *  menampilkannya dalam bentuk slider.
 * ============================================================================
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ----------------------------------------------------------------------------
 * 1. REGISTER CUSTOM POST TYPE "pegawai"
 * -------------------------------------------------------------------------- */
function bmkg_register_pegawai_cpt() {
	$labels = array(
		'name'               => 'Pegawai',
		'singular_name'      => 'Pegawai',
		'menu_name'          => 'Pegawai',
		'add_new'            => 'Tambah Pegawai',
		'add_new_item'       => 'Tambah Pegawai Baru',
		'edit_item'          => 'Edit Pegawai',
		'new_item'           => 'Pegawai Baru',
		'view_item'          => 'Lihat Pegawai',
		'search_items'       => 'Cari Pegawai',
		'not_found'          => 'Tidak ada pegawai ditemukan',
		'not_found_in_trash' => 'Tidak ada pegawai di tempat sampah',
		'all_items'          => 'Semua Pegawai',
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,        // tidak punya halaman single sendiri
		'show_ui'             => true,         // tetap tampil di admin
		'show_in_menu'        => true,
		'menu_position'       => 25,
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array( 'title', 'thumbnail' ), // Judul = Nama, Thumbnail = Foto
		'has_archive'         => false,
		'rewrite'             => false,
		'exclude_from_search' => true,
		'show_in_rest'        => false,
	);

	register_post_type( 'pegawai', $args );
}
add_action( 'init', 'bmkg_register_pegawai_cpt' );

/* ----------------------------------------------------------------------------
 * 2. META BOX — Jabatan, NIP, Urutan
 * -------------------------------------------------------------------------- */
function bmkg_add_pegawai_meta_box() {
	add_meta_box(
		'bmkg_pegawai_detail',
		'Detail Pegawai',
		'bmkg_pegawai_meta_box_callback',
		'pegawai',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'bmkg_add_pegawai_meta_box' );

function bmkg_pegawai_meta_box_callback( $post ) {
	wp_nonce_field( 'bmkg_save_pegawai_meta', 'bmkg_pegawai_nonce' );

	$jabatan = get_post_meta( $post->ID, '_bmkg_pegawai_jabatan', true );
	$nip     = get_post_meta( $post->ID, '_bmkg_pegawai_nip', true );
	$urutan  = get_post_meta( $post->ID, '_bmkg_pegawai_urutan', true );
	?>
	<style>
		.bmkg-pegawai-field { margin-bottom: 16px; }
		.bmkg-pegawai-field label { display:block; font-weight:600; margin-bottom:6px; }
		.bmkg-pegawai-field input { width:100%; padding:8px 10px; }
		.bmkg-pegawai-field .desc { color:#666; font-size:12px; margin-top:4px; }
	</style>

	<p style="background:#eef4fb;padding:10px 14px;border-radius:6px;color:#1a3a6c;">
		<strong>Nama pegawai</strong> diambil dari kolom <strong>Judul</strong> di atas.
		<strong>Foto pegawai</strong> diambil dari <strong>Gambar Andalan / Featured Image</strong> di panel kanan.
	</p>

	<div class="bmkg-pegawai-field">
		<label for="bmkg_pegawai_jabatan">Jabatan</label>
		<input type="text" id="bmkg_pegawai_jabatan" name="bmkg_pegawai_jabatan"
			value="<?php echo esc_attr( $jabatan ); ?>"
			placeholder="Contoh: Kepala Stasiun">
	</div>

	<div class="bmkg-pegawai-field">
		<label for="bmkg_pegawai_nip">NIP</label>
		<input type="text" id="bmkg_pegawai_nip" name="bmkg_pegawai_nip"
			value="<?php echo esc_attr( $nip ); ?>"
			placeholder="Contoh: 19850101 200901 1 001">
	</div>

	<div class="bmkg-pegawai-field">
		<label for="bmkg_pegawai_urutan">Urutan Tampil</label>
		<input type="number" id="bmkg_pegawai_urutan" name="bmkg_pegawai_urutan"
			value="<?php echo esc_attr( $urutan ); ?>"
			placeholder="1" min="0" step="1">
		<span class="desc">Angka kecil tampil lebih dulu. Kosongkan jika tidak perlu diurutkan.</span>
	</div>
	<?php
}

/* ----------------------------------------------------------------------------
 * 3. SIMPAN META
 * -------------------------------------------------------------------------- */
function bmkg_save_pegawai_meta( $post_id ) {
	if ( ! isset( $_POST['bmkg_pegawai_nonce'] ) || ! wp_verify_nonce( $_POST['bmkg_pegawai_nonce'], 'bmkg_save_pegawai_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['bmkg_pegawai_jabatan'] ) ) {
		update_post_meta( $post_id, '_bmkg_pegawai_jabatan', sanitize_text_field( $_POST['bmkg_pegawai_jabatan'] ) );
	}
	if ( isset( $_POST['bmkg_pegawai_nip'] ) ) {
		update_post_meta( $post_id, '_bmkg_pegawai_nip', sanitize_text_field( $_POST['bmkg_pegawai_nip'] ) );
	}
	if ( isset( $_POST['bmkg_pegawai_urutan'] ) ) {
		update_post_meta( $post_id, '_bmkg_pegawai_urutan', absint( $_POST['bmkg_pegawai_urutan'] ) );
	}
}
add_action( 'save_post_pegawai', 'bmkg_save_pegawai_meta' );

/* ----------------------------------------------------------------------------
 * 4. KOLOM TAMBAHAN DI DAFTAR ADMIN (Foto, Jabatan, NIP, Urutan)
 * -------------------------------------------------------------------------- */
function bmkg_pegawai_admin_columns( $columns ) {
	$new = array();
	$new['cb']              = $columns['cb'];
	$new['bmkg_foto']       = 'Foto';
	$new['title']           = 'Nama';
	$new['bmkg_jabatan']    = 'Jabatan';
	$new['bmkg_nip']        = 'NIP';
	$new['bmkg_urutan']     = 'Urutan';
	$new['date']            = $columns['date'];
	return $new;
}
add_filter( 'manage_pegawai_posts_columns', 'bmkg_pegawai_admin_columns' );

function bmkg_pegawai_admin_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'bmkg_foto':
			if ( has_post_thumbnail( $post_id ) ) {
				echo get_the_post_thumbnail( $post_id, array( 50, 50 ), array( 'style' => 'border-radius:50%;object-fit:cover;' ) );
			} else {
				echo '&mdash;';
			}
			break;
		case 'bmkg_jabatan':
			echo esc_html( get_post_meta( $post_id, '_bmkg_pegawai_jabatan', true ) ?: '&mdash;' );
			break;
		case 'bmkg_nip':
			echo esc_html( get_post_meta( $post_id, '_bmkg_pegawai_nip', true ) ?: '&mdash;' );
			break;
		case 'bmkg_urutan':
			echo esc_html( get_post_meta( $post_id, '_bmkg_pegawai_urutan', true ) ?: '&mdash;' );
			break;
	}
}
add_action( 'manage_pegawai_posts_custom_column', 'bmkg_pegawai_admin_column_content', 10, 2 );

/* Buat kolom "Urutan" bisa di-sort */
function bmkg_pegawai_sortable_columns( $columns ) {
	$columns['bmkg_urutan'] = 'bmkg_urutan';
	return $columns;
}
add_filter( 'manage_edit-pegawai_sortable_columns', 'bmkg_pegawai_sortable_columns' );

/* ----------------------------------------------------------------------------
 * 5. HELPER — Ambil semua pegawai terurut (dipakai oleh template)
 * -------------------------------------------------------------------------- */
function bmkg_get_pegawai_list() {
	return new WP_Query( array(
		'post_type'      => 'pegawai',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => '_bmkg_pegawai_urutan',
		'orderby'        => array(
			'meta_value_num' => 'ASC',
			'date'           => 'DESC',
		),
	) );
}
/* ==== [END] CPT PEGAWAI ==== */

/* ==== [BEGIN] PROXY PRAKIRAAN CUACA BMKG (api.bmkg.go.id, cache 30 menit) ==== */
function bmkg_betoambari_adm4_map() {
	return array(
		/* Kecamatan Betoambari */
		'74.72.01.1007' => array( 'kel' => 'Katobengke',         'kec' => 'Betoambari', 'lat' => -5.4790, 'lng' => 122.5759 ),
		'74.72.01.1016' => array( 'kel' => 'Labalawa',           'kec' => 'Betoambari', 'lat' => -5.5143, 'lng' => 122.5905 ),
		'74.72.01.1014' => array( 'kel' => 'Lipu',               'kec' => 'Betoambari', 'lat' => -5.4890, 'lng' => 122.5814 ),
		'74.72.01.1013' => array( 'kel' => 'Sulaa',              'kec' => 'Betoambari', 'lat' => -5.5129, 'lng' => 122.5689 ),
		'74.72.01.1012' => array( 'kel' => 'Waborobo',           'kec' => 'Betoambari', 'lat' => -5.5018, 'lng' => 122.6033 ),
		/* Kecamatan Wolio */
		'74.72.02.1003' => array( 'kel' => 'Bataraguru',         'kec' => 'Wolio',      'lat' => -5.4645, 'lng' => 122.6079 ),
		'74.72.02.1005' => array( 'kel' => 'Batulo',             'kec' => 'Wolio',      'lat' => -5.4595, 'lng' => 122.6158 ),
		'74.72.02.1011' => array( 'kel' => 'Bukit Wolio Indah',  'kec' => 'Wolio',      'lat' => -5.4792, 'lng' => 122.6154 ),
		'74.72.02.1007' => array( 'kel' => 'Kadolo Katapi',      'kec' => 'Wolio',      'lat' => -5.4955, 'lng' => 122.6399 ),
		'74.72.02.1002' => array( 'kel' => 'Tomba',              'kec' => 'Wolio',      'lat' => -5.4588, 'lng' => 122.6085 ),
		'74.72.02.1001' => array( 'kel' => 'Wale',               'kec' => 'Wolio',      'lat' => -5.4563, 'lng' => 122.6088 ),
		'74.72.02.1004' => array( 'kel' => 'Wangkanapi',         'kec' => 'Wolio',      'lat' => -5.4628, 'lng' => 122.6125 ),
		/* Kecamatan Sorawolio */
		'74.72.03.1004' => array( 'kel' => 'Bugi',               'kec' => 'Sorawolio',  'lat' => -5.4121, 'lng' => 122.7292 ),
		'74.72.03.1003' => array( 'kel' => 'Gonda Baru',         'kec' => 'Sorawolio',  'lat' => -5.4564, 'lng' => 122.7454 ),
		'74.72.03.1001' => array( 'kel' => 'Kaisabu Baru',       'kec' => 'Sorawolio',  'lat' => -5.4368, 'lng' => 122.6941 ),
		'74.72.03.1002' => array( 'kel' => 'Karya Baru',         'kec' => 'Sorawolio',  'lat' => -5.4523, 'lng' => 122.7022 ),
		/* Kecamatan Bungi */
		'74.72.04.1003' => array( 'kel' => 'Kampeonaho',         'kec' => 'Bungi',      'lat' => -5.3401, 'lng' => 122.7046 ),
		'74.72.04.1001' => array( 'kel' => 'Liabuku',            'kec' => 'Bungi',      'lat' => -5.4112, 'lng' => 122.6655 ),
		'74.72.04.1002' => array( 'kel' => 'Ngkaring Karing',    'kec' => 'Bungi',      'lat' => -5.3617, 'lng' => 122.7004 ),
		'74.72.04.1010' => array( 'kel' => 'Tampuna',            'kec' => 'Bungi',      'lat' => -5.3345, 'lng' => 122.6862 ),
		'74.72.04.1008' => array( 'kel' => 'Waliabuku',          'kec' => 'Bungi',      'lat' => -5.3870, 'lng' => 122.6895 ),
		/* Kecamatan Kokalukuna */
		'74.72.05.1006' => array( 'kel' => 'Kadolo',             'kec' => 'Kokalukuna', 'lat' => -5.4645, 'lng' => 122.6235 ),
		'74.72.05.1005' => array( 'kel' => 'Kadolomoko',         'kec' => 'Kokalukuna', 'lat' => -5.4566, 'lng' => 122.6363 ),
		'74.72.05.1003' => array( 'kel' => 'Lakologou',          'kec' => 'Kokalukuna', 'lat' => -5.4169, 'lng' => 122.6525 ),
		'74.72.05.1001' => array( 'kel' => 'Liwuto',             'kec' => 'Kokalukuna', 'lat' => -5.4295, 'lng' => 122.6267 ),
		'74.72.05.1002' => array( 'kel' => 'Sukanaeyo',          'kec' => 'Kokalukuna', 'lat' => -5.4261, 'lng' => 122.6270 ),
		'74.72.05.1004' => array( 'kel' => 'Waruruma',           'kec' => 'Kokalukuna', 'lat' => -5.4348, 'lng' => 122.6606 ),
		/* Kecamatan Murhum */
		'74.72.06.1010' => array( 'kel' => 'Baadia',             'kec' => 'Murhum',     'lat' => -5.4844, 'lng' => 122.6040 ),
		'74.72.06.1008' => array( 'kel' => 'Lamangga',           'kec' => 'Murhum',     'lat' => -5.4705, 'lng' => 122.5986 ),
		'74.72.06.1009' => array( 'kel' => 'Melai',              'kec' => 'Murhum',     'lat' => -5.4736, 'lng' => 122.6036 ),
		'74.72.06.1011' => array( 'kel' => 'Tanganapada',        'kec' => 'Murhum',     'lat' => -5.4719, 'lng' => 122.5931 ),
		'74.72.06.1007' => array( 'kel' => 'Wajo',               'kec' => 'Murhum',     'lat' => -5.4672, 'lng' => 122.6035 ),
		/* Kecamatan Lea-Lea */
		'74.72.07.1002' => array( 'kel' => 'Kalia-Lia',          'kec' => 'Lea-Lea',    'lat' => -5.3813, 'lng' => 122.6402 ),
		'74.72.07.1005' => array( 'kel' => 'Kantalai',           'kec' => 'Lea-Lea',    'lat' => -5.3862, 'lng' => 122.6522 ),
		'74.72.07.1004' => array( 'kel' => 'Kolese',             'kec' => 'Lea-Lea',    'lat' => -5.4012, 'lng' => 122.6236 ),
		'74.72.07.1001' => array( 'kel' => 'Lowu-Lowu',          'kec' => 'Lea-Lea',    'lat' => -5.4056, 'lng' => 122.6422 ),
		'74.72.07.1003' => array( 'kel' => 'Palabusa',           'kec' => 'Lea-Lea',    'lat' => -5.3375, 'lng' => 122.6641 ),
		/* Kecamatan Batupoaro */
		'74.72.08.1001' => array( 'kel' => 'Bonebone',           'kec' => 'Batupoaro',  'lat' => -5.4656, 'lng' => 122.5895 ),
		'74.72.08.1004' => array( 'kel' => 'Kaobula',            'kec' => 'Batupoaro',  'lat' => -5.4571, 'lng' => 122.5982 ),
		'74.72.08.1005' => array( 'kel' => 'Lanto',              'kec' => 'Batupoaro',  'lat' => -5.4597, 'lng' => 122.6001 ),
		'74.72.08.1006' => array( 'kel' => 'Nganganaumala',      'kec' => 'Batupoaro',  'lat' => -5.4580, 'lng' => 122.6020 ),
		'74.72.08.1002' => array( 'kel' => 'Tarafu',             'kec' => 'Batupoaro',  'lat' => -5.4625, 'lng' => 122.5928 ),
		'74.72.08.1003' => array( 'kel' => 'Wameo',              'kec' => 'Batupoaro',  'lat' => -5.4581, 'lng' => 122.5952 ),
	);
}

/* Titik representatif 8 kecamatan Kota Baubau — untuk marker peta */
function bmkg_betoambari_kecamatan_titik() {
	return array(
		array( 'nama' => 'Betoambari', 'lat' => -5.4973, 'lng' => 122.5841, 'adm4' => '74.72.01.1013' ),
		array( 'nama' => 'Wolio',      'lat' => -5.4680, 'lng' => 122.6155, 'adm4' => '74.72.02.1001' ),
		array( 'nama' => 'Sorawolio',  'lat' => -5.4394, 'lng' => 122.7178, 'adm4' => '74.72.03.1001' ),
		array( 'nama' => 'Bungi',      'lat' => -5.3670, 'lng' => 122.6890, 'adm4' => '74.72.04.1001' ),
		array( 'nama' => 'Kokalukuna', 'lat' => -5.4380, 'lng' => 122.6380, 'adm4' => '74.72.05.1001' ),
		array( 'nama' => 'Murhum',     'lat' => -5.4735, 'lng' => 122.6006, 'adm4' => '74.72.06.1007' ),
		array( 'nama' => 'Lea-Lea',    'lat' => -5.3823, 'lng' => 122.6445, 'adm4' => '74.72.07.1002' ),
		array( 'nama' => 'Batupoaro',  'lat' => -5.4602, 'lng' => 122.5963, 'adm4' => '74.72.08.1001' ),
	);
}

function bmkg_rest_cuaca( $request = null ) {
	$kec = '';
	if ( $request instanceof WP_REST_Request ) {
		$kec = sanitize_text_field( (string) $request->get_param( 'kec' ) );
	}

	$all_map = bmkg_betoambari_adm4_map();
	$map = $all_map;
	if ( $kec ) {
		$map = array_filter( $all_map, function ( $info ) use ( $kec ) {
			return 0 === strcasecmp( $info['kec'], $kec );
		} );
	}

	$cache_key = 'bmkg_cuaca_betoambari_v4_' . md5( strtolower( $kec ? $kec : 'semua' ) );
	$cached = get_transient( $cache_key );
	if ( false !== $cached ) {
		return rest_ensure_response( $cached );
	}

	$out = array(
		'updated' => current_time( 'mysql' ),
		'source'  => 'BMKG Data Terbuka',
		'data'    => array(),
	);

	foreach ( $map as $adm4 => $info ) {
		$res = wp_remote_get(
			'https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=' . rawurlencode( $adm4 ),
			array(
				'timeout'     => 5,
				'redirection' => 2,
				'user-agent'  => 'BMKG-Betoambari-Website/4.0; ' . home_url( '/' ),
			)
		);
		if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
			continue;
		}
		$body = json_decode( wp_remote_retrieve_body( $res ), true );
		$days = isset( $body['data'][0]['cuaca'] ) ? $body['data'][0]['cuaca'] : array();

		$entries = array();
		foreach ( $days as $day ) {
			foreach ( (array) $day as $e ) {
				$entries[] = array(
					'dt'            => isset( $e['local_datetime'] ) ? $e['local_datetime'] : '',
					't'             => isset( $e['t'] ) ? $e['t'] : null,
					'hu'            => isset( $e['hu'] ) ? $e['hu'] : null,
					'code'          => isset( $e['weather'] ) ? (int) $e['weather'] : 3,
					'desc'          => isset( $e['weather_desc'] ) ? $e['weather_desc'] : '',
					'img'           => isset( $e['image'] ) ? $e['image'] : '',
					'ws'            => isset( $e['ws'] ) ? $e['ws'] : null,
					'wd'            => isset( $e['wd'] ) ? $e['wd'] : '',
					'wd_deg'        => isset( $e['wd_deg'] ) ? $e['wd_deg'] : null,
					'tcc'           => isset( $e['tcc'] ) ? $e['tcc'] : null,
					'vs_text'       => isset( $e['vs_text'] ) ? $e['vs_text'] : '',
					'tp'            => isset( $e['tp'] ) ? $e['tp'] : null,
					'analysis_date' => isset( $e['analysis_date'] ) ? $e['analysis_date'] : '',
				);
			}
		}
		if ( $entries ) {
			$out['data'][ $adm4 ] = array(
				'kel'     => $info['kel'],
				'kec'     => $info['kec'],
				'lat'     => $info['lat'],
				'lng'     => $info['lng'],
				'entries' => $entries,
			);
		}
	}

	/* 30 menit bila berhasil; 5 menit bila upstream sedang kosong/terblokir. */
	set_transient( $cache_key, $out, empty( $out['data'] ) ? 5 * MINUTE_IN_SECONDS : 30 * MINUTE_IN_SECONDS );
	return rest_ensure_response( $out );
}

function bmkg_register_rest_routes() {
	register_rest_route( 'bmkg/v1', '/cuaca', array(
		'methods'             => 'GET',
		'callback'            => 'bmkg_rest_cuaca',
		'permission_callback' => '__return_true',
		'args'                => array(
			'kec' => array( 'required' => false, 'sanitize_callback' => 'sanitize_text_field' ),
		),
	) );
}
add_action( 'rest_api_init', 'bmkg_register_rest_routes' );
/* ==== [END] PROXY PRAKIRAAN CUACA ==== */

/* ==== [BEGIN] PROXY PETIR REALTIME (fallback aman bila sumber tak tersedia) ==== */
function bmkg_rest_petir() {
	$cached = get_transient( 'bmkg_petir_sultra' );
	if ( false !== $cached ) {
		return rest_ensure_response( $cached );
	}

	$out = array( 'updated' => current_time( 'mysql' ), 'strikes' => array() );

	/* Sumber lightning BMKG (petir) — endpoint dapat berubah; dibungkus try/catch lewat cek response. */
	$sources = array(
		'https://balai2.slamet.bmkg.go.id/petir/data.json',
		'https://cuaca.bmkg.go.id/data/public/lightning/sultra.json',
	);

	foreach ( $sources as $url ) {
		$res = wp_remote_get( $url, array( 'timeout' => 6, 'sslverify' => false ) );
		if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) {
			continue;
		}
		$body = json_decode( wp_remote_retrieve_body( $res ), true );
		if ( empty( $body ) ) { continue; }

		/* Normalisasi: cari array titik dengan lat/lon/time. Struktur bervariasi antar sumber. */
		$rows = array();
		if ( isset( $body['features'] ) ) {
			foreach ( $body['features'] as $f ) {
				$g = isset( $f['geometry']['coordinates'] ) ? $f['geometry']['coordinates'] : null;
				$a = isset( $f['properties'] ) ? $f['properties'] : array();
				if ( $g ) {
					$rows[] = array(
						'lat'  => (float) $g[1],
						'lng'  => (float) $g[0],
						'time' => isset( $a['time'] ) ? (int) $a['time'] : ( isset( $a['timestamp'] ) ? (int) $a['timestamp'] * 1000 : time() * 1000 ),
					);
				}
			}
		} elseif ( is_array( $body ) ) {
			foreach ( $body as $p ) {
				if ( isset( $p['lat'], $p['lon'] ) ) {
					$rows[] = array( 'lat' => (float) $p['lat'], 'lng' => (float) $p['lon'], 'time' => isset( $p['time'] ) ? (int) $p['time'] : time() * 1000 );
				} elseif ( isset( $p['latitude'], $p['longitude'] ) ) {
					$rows[] = array( 'lat' => (float) $p['latitude'], 'lng' => (float) $p['longitude'], 'time' => isset( $p['time'] ) ? (int) $p['time'] : time() * 1000 );
				}
			}
		}

		/* Filter kotak wilayah Sultra (lat -7..-3, lng 120..125) */
		$rows = array_filter( $rows, function( $r ) {
			return $r['lat'] > -7 && $r['lat'] < -3 && $r['lng'] > 120 && $r['lng'] < 125;
		} );

		if ( $rows ) {
			$out['strikes'] = array_values( $rows );
			break;
		}
	}

	/* Cache 1 menit (realtime) */
	set_transient( 'bmkg_petir_sultra', $out, MINUTE_IN_SECONDS );
	return rest_ensure_response( $out );
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'bmkg/v1', '/petir', array(
		'methods'             => 'GET',
		'callback'            => 'bmkg_rest_petir',
		'permission_callback' => '__return_true',
	) );
} );
/* ==== [END] PROXY PETIR REALTIME ==== */


/* ==== [BEGIN] Virtual routing untuk halaman khusus (petir, maritim & flight documentation) ==== */
/* Mendaftarkan rewrite rule agar /petir-realtime/ dan /display-cuaca-maritim/
   dapat diakses TANPA perlu membuat WP Page manual. */
add_action( 'init', function () {
	add_rewrite_rule( '^petir-realtime/?$', 'index.php?bmkg_vpage=petir-realtime', 'top' );
	add_rewrite_rule( '^display-cuaca-maritim/?$', 'index.php?bmkg_vpage=display-cuaca-maritim', 'top' );
	add_rewrite_rule( '^flight-documentation/?$', 'index.php?bmkg_vpage=flight-documentation', 'top' );
} );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'bmkg_vpage';
	return $vars;
} );

add_action( 'template_include', 'bmkg_virtual_template', 99 );
function bmkg_virtual_template( $template ) {
	$vpage = get_query_var( 'bmkg_vpage' );
	if ( 'petir-realtime' === $vpage ) {
		$f = get_template_directory() . '/petir-realtime.php';
		if ( file_exists( $f ) ) { status_header( 200 ); return $f; }
	}
	if ( 'display-cuaca-maritim' === $vpage ) {
		$f = get_template_directory() . '/display-cuaca-maritim.php';
		if ( file_exists( $f ) ) { status_header( 200 ); return $f; }
	}
	if ( 'flight-documentation' === $vpage ) {
		$f = get_template_directory() . '/flight-documentation.php';
		if ( file_exists( $f ) ) { status_header( 200 ); return $f; }
	}
	return $template;
}

/* Pastikan WordPress tidak menganggap virtual page sebagai 404 */
add_action( 'parse_query', function ( $q ) {
	if ( ! empty( $q->query_vars['bmkg_vpage'] ) ) {
		$q->is_404  = false;
		$q->is_home = false;
	}
} );

/* Flush rewrite sekali saat tema diaktifkan */
add_action( 'after_switch_theme', function () {
	add_rewrite_rule( '^petir-realtime/?$', 'index.php?bmkg_vpage=petir-realtime', 'top' );
	add_rewrite_rule( '^display-cuaca-maritim/?$', 'index.php?bmkg_vpage=display-cuaca-maritim', 'top' );
	add_rewrite_rule( '^flight-documentation/?$', 'index.php?bmkg_vpage=flight-documentation', 'top' );
	flush_rewrite_rules();
} );
/* ==== [END] Virtual routing ==== */

/* ==== [BEGIN] Helper YouTube embed untuk front page ==== */
function bmkg_get_youtube_id( $url ) {
	if ( ! $url ) { return ''; }
	$url = trim( $url );
	// youtu.be/ID
	if ( preg_match( '~youtu\.be/([A-Za-z0-9_-]{6,})~', $url, $m ) ) { return $m[1]; }
	// youtube.com/watch?v=ID
	if ( preg_match( '~[?&]v=([A-Za-z0-9_-]{6,})~', $url, $m ) ) { return $m[1]; }
	// youtube.com/embed/ID
	if ( preg_match( '~embed/([A-Za-z0-9_-]{6,})~', $url, $m ) ) { return $m[1]; }
	// shorts
	if ( preg_match( '~shorts/([A-Za-z0-9_-]{6,})~', $url, $m ) ) { return $m[1]; }
	return '';
}
/* ==== [END] Helper YouTube embed ==== */

