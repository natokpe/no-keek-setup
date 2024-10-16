<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

declare(strict_types = 1);

namespace NatOkpe\Wp\Plugin\KeekSetup;

use NatOkpe\Wp\Plugin\KeekSetup\Utils\Clock;

use \DateTime;

use \WP_Post;
use \WP_Role;
use \WP_User_Query;
use \WP_Query;
use \WP_User;

// use PHPMailer\PHPMailer\PHPMailer;

use function \add_action;
use function \add_theme_support;
use function \get_option;
use function \plugin_dir_uri;
use function \plugin_dir_path;
use function \flush_rewrite_rules;
use function \unregister_post_type;

use function \new_cmb2_box;

class Engine
{
    public
    function __construct()
    {
    }

    /**
     * Register custom taxonomies
     */
    public static
    function setup_taxonomy()
    {
        foreach (Config::get('tax') as $_ => $__) {
            register_taxonomy($_, $__['object_type'], $__['args']);

            register_taxonomy_for_object_type(
                ($__['taxonomy'] ?? $_),
                $__['object_type']
            );
        }
    }

    /**
     * Register custom post types
     */
    public static
    function setup_post_types()
    {
        foreach (Config::get('post_types') as $_ => $__) {
            register_post_type($_, $__);
        }
    }

    /**
     * Remove custom post types
     */
    public static
    function unset_post_types()
    {
        foreach (Config::get('post_types') as $_ => $__) {
            unregister_post_type($_);
        }
    }

    /**
     * 
     */
    public static
    function setup_meta_boxes()
    {
        foreach(Config::get('meta') as $__b) {
            $box = $__b;
            unset($box['box-fields']);

            $cmb = new_cmb2_box($box);

            foreach ($__b['box-fields'] as $__f) {
                $field = $__f;

                if ($field['type'] === 'group') {
                    unset($field['group-fields']);

                    $group = $cmb->add_field($field);

                    foreach ($__f['group-fields'] as $group_field) {
                        $cmb->add_group_field($group, $group_field);
                    }
                } else {
                    $cmb->add_field($field);
                }
            }
        }
    }

    /**
     * 
     */
    public static
    function setup_options()
    {
        foreach(Config::get('options') as $__p) {
            $page = $__p;
            unset($page['page-sections']);

            $cmb = new_cmb2_box($page);

            foreach ($__p['page-sections'] as $__s) {
                $section = $__s;

                if (empty($section['fields'] ?? [])) {
                    continue;
                }

                unset($section['fields']);

                if (array_key_exists('name', $section)) {
                    $cmb->add_field($section);
                }

                foreach ($__s['fields'] as $__f) {
                    $field = $__f;

                    if ($field['type'] === 'group') {
                        unset($field['group-fields']);

                        $group = $cmb->add_field($field);

                        foreach ($__f['group-fields'] as $group_field) {
                            $cmb->add_group_field($group, $group_field);
                        }
                    } else {
                        $cmb->add_field($field);
                    }
                }
            }
        }
    }

    /**
     * 
     */
    public static
    function setup_roles()
    {
        foreach([
            'subscriber',
            'contributor',
            'author',
            'editor'
        ] as $_role) {
            remove_role($_role);
        }

        foreach(Config::get('roles') as $_role_id => $__) {
            $caps = array_fill_keys($__['cap'], true);

            add_role($_role_id, $__['name'], $caps);
        }

        $a_role = get_role('administrator');

        if ($a_role instanceOf WP_Role) {
            foreach (Config::get('post_types') as $_p => $__p) {
                $pcaps = (array) ((get_post_type_object($_p))->cap ?? []);

                if (! empty($pcaps)) {
                    foreach ($pcaps as $_c) {
                        $a_role->add_cap($_c, true);
                    }
                }
            }
        }
    }

    /**
     * 
     */
    public static
    function unset_roles()
    {
        foreach(Config::get('roles') as $_role_id => $__) {
            remove_role($_role_id);
        }

        $a_role = get_role('administrator');

        if ($a_role instanceOf WP_Role) {
            foreach (Config::get('post_types') as $_p => $__p) {
                $pcaps = (array) ((get_post_type_object($_p))->cap ?? []);

                if (! empty($pcaps)) {
                    foreach ($pcaps as $_c) {
                        $a_role->remove_cap($_c);
                    }
                }
            }
        }
    }

    /**
     * Activate the plugin.
     */
    public static
    function activate()
    {
        // Trigger our function that registers the custom post type plugin.
        self::setup_post_types();

        self::setup_roles();

        // Clear the permalinks after the post type has been registered.
        flush_rewrite_rules(); 
    }

    /**
     * Deactivation hook.
     */
    public static
    function deactivate()
    {
        // Unregister the post type, so the rules are no longer in memory.
        self::unset_post_types();

        self::unset_roles();

        // Clear the permalinks to remove our post type's rules from the database.
        flush_rewrite_rules();
    }

    /**
     * Uninstall hook.
     */
    public static
    function uninstall()
    {
        // Unregister the post type, so the rules are no longer in memory.
        self::unset_post_types();

        self::unset_roles();

        // Clear the permalinks to remove our post type's rules from the database.
        flush_rewrite_rules();
    }

    /**
     * 
     */
    public static
    function url(...$parts): string
    {
        $a = [];
        $p = [];
        $d = plugin_dir_url(
            dirname(dirname(__FILE__))
            . DIRECTORY_SEPARATOR . 'no-keek-setup.php'
        );

        foreach ($parts as $part) {
            if (is_array($part)) {
                $a = array_merge($a, $part);
            } else {
                if (is_scalar($part)) {
                    $a[] = $part;
                }
            }
        }

        foreach ($a as $part) {
            $p[] = ltrim((string) $part, '/');
        }

        $d = ! empty($p) ? rtrim($d, '/') : $d;

        return implode('/', array_merge([$d], $p));
    }

    /**
     * 
     */
    public static
    function dir(...$parts): string
    {
        $a = [];
        $p = [];
        $d = dirname(dirname(__FILE__));

        foreach ($parts as $part) {
            if (is_array($part)) {
                $a = array_merge($a, $part);
            } else {
                if (is_scalar($part)) {
                    $a[] = $part;
                }
            }
        }

        foreach ($a as $part) {
            $p[] = ltrim((string) $part, '\\/');
        }

        $d = ! empty($p) ? rtrim($d, '\\/') : $d;

        return implode(DIRECTORY_SEPARATOR, array_merge([$d], $p));
    }

    /**
     * 
     */
    public static
    function dirAssets(...$parts): string
    {
        return self::dir('assets', ...$parts);
    }

    /**
     * 
     */
    public static
    function dirConfig(...$parts): string
    {
        return self::dir('config', ...$parts);
    }

    /**
     * 
     */
    public static
    function path(...$parts): string
    {
        $a = [];
        $p = [];

        foreach ($parts as $part) {
            if (is_array($part)) {
                $a = array_merge($a, $part);
            } else {
                if (is_scalar($part)) {
                    $a[] = $part;
                }
            }
        }

        foreach ($a as $part) {
            if (is_scalar($part)) {
                $p[] = $part;
            }
        }

        return implode(DIRECTORY_SEPARATOR, $p);
    }

    /**
     * 
     */
    public static
    function get_option(string $key = '', $default = false, string $store = 'no_so')
    {
        if (function_exists( 'cmb2_get_option' ) ) {
            // Use cmb2_get_option as it passes through some key filters.
            return cmb2_get_option($store, $key, $default );
        }

        // Fallback to get_option if CMB2 is not loaded yet.
        $opts = get_option( $store, $default );

        $val = $default;

        if ( 'all' == $key ) {
            $val = $opts;
        } elseif ( is_array( $opts ) && array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
            $val = $opts[ $key ];
        }

        return $val;
    }

    /**
     * 
     */
    public static
    function env(?string $key = null, $alt = null): mixed
    {
        return is_null($key) ? ($_ENV ?? []) : ($_ENV[$key] ?? $alt);
    }

    /**
     * @param $plugin_file string URI for the plugin file
     */
    public static
    function start(string $plugin_file)
    {
        register_activation_hook($plugin_file, [__CLASS__, 'activate']);
        register_deactivation_hook($plugin_file, [__CLASS__, 'deactivate']);
        register_uninstall_hook($plugin_file, [__CLASS__, 'uninstall']);

        add_action('init', [__CLASS__, 'setup_post_types']);
        add_action('init', [__CLASS__, 'setup_taxonomy']);

        add_action('cmb2_admin_init', [__CLASS__, 'setup_meta_boxes']);
        add_action('cmb2_admin_init', [__CLASS__, 'setup_options']);

        add_action('after_setup_theme', function () {
            foreach (Config::get('engine')['image_sizes'] as $slug => $__) {
                if (! isset($__['width'])) {
                    continue;
                }

                add_image_size(
                    (string) $slug,
                    (int) $__['width'],
                    ((int) $__['height']) ?? null,
                    ((bool) $__['crop']) ?? true,
                );
            }
        });

        add_action('admin_init', function () {
            add_filter('admin_footer_text', function ($text)
            {
                $new_txt = Config::get('engine')['admin_footer_text'];

                if (is_callable($new_txt)) {
                    $new_txt = $new_txt($text);
                }

                return is_string($new_txt) ? $new_txt : '';
            });

            add_filter('update_footer', function ($text) {
                $new_txt = Config::get('engine')['update_footer'];

                if (is_callable($new_txt)) {
                    $new_txt = $new_txt($text);
                }

                return is_string($new_txt) ? $new_txt : '';
            });
        });

        add_filter('query_vars', function ($qvars) {
            $n_qvars = $qvars;
            return $n_qvars + Config::get('engine')['query_vars'];
        });

        add_filter('upload_mimes', function ($mimes) {
            $new_mimes = Config::get('engine')['upload_mimes'];

            if (is_callable($new_mimes)) {
                $new_mimes = $new_mimes($mimes);
            }

            return is_array($new_mimes) ? $new_mimes : [];
        });

        add_filter('show_admin_bar' , function () {
            $show = Config::get('engine')['show_admin_bar'];
            return is_bool($show) ? $show : true;
        });

        // add_filter('manage_member_posts_columns', function ($posts_columns) {
        //     $new_cols = [
        //         'cb' => $posts_columns['cb'],
        //         'member_name' => 'Name',
        //         'member_photo' => 'Photo',
        //     ] + $posts_columns;

        //     unset($new_cols['title']);

        //     return $new_cols;
        // });

        // add_filter('manage_edit-member_sortable_columns', function ($columns) {
        //     return (['member_name' => 'Name'] + $columns);
        // });

        // add_action('manage_member_posts_custom_column', function ($column_name, $post_id) {
        //     switch ($column_name) {
        //         case 'member_name':
        //             edit_post_link(
        //                 get_post_meta($post_id, 'ecjpmbnm_full', [])[0] ?? '',
        //                 '<strong>',
        //                 '</strong>',
        //                 $post_id,
        //                 ''
        //             );
        //             break;

        //         case 'member_photo':
        //             if (has_post_thumbnail($post_id)) {
        //                 echo get_the_post_thumbnail($post_id, 'image-32');
        //             } else {
        //                 printf(
        //                     '<img width="32" height="32" src="%s" '
        //                     . 'alt="" decoding="async" loading="lazy" />',

        //                     Engine::url('assets/img/member.webp')
        //                 );
        //             }
        //             break;
        //     }
        // }, 10, 2);

        // add_filter('list_table_primary_column', function ($default, $screen) {
        //     $new_default = $default;

        //     switch ($screen) {
        //         case 'edit-member':
        //             $new_default = 'member_name';
        //             break;
        //     }

        //     return $new_default;
        // }, 10, 2 );

        // add_action('admin_menu', function () {
        //     add_filter('post_row_actions', function ($actions, $post) {
        //         $new_actions = [];

        //         switch (get_post_type($post)) {
        //             case 'member':
        //                 $new_actions = $actions;
        //                 unset($new_actions['view']);
        //                 return $new_actions;
        //                 break;

        //                 default:
        //                 return $actions;
        //         }
        //     }, 10, 2);
        // });

        add_action('admin_bar_menu', function ($wp_admin_bar) {
            $wp_admin_bar->remove_node('wp-logo');
        });

        add_filter('users_list_table_query_args', function ($args) {
            $new_args   = $args;
            $user_roles = wp_get_current_user()->roles;

            if (count(array_intersect($user_roles, ['administrator'])) > 0) {
            }

            if (count(array_intersect($user_roles, [
                'senior_administrator'
            ])) > 0) {
                $new_args['role__not_in'] = [
                    'administrator'
                ];
            }

            if (count(array_intersect($user_roles, [
                'junior_administrator'
            ])) > 0) {
                $new_args['role__not_in'] = [
                    'administrator',
                    // 'senior_administrator',
                    // 'junior_administrator',
                ];
            }

            return $new_args;
        });

        // add_filter('views_users', function ($views) {
        //     return $views;
        // });

        add_filter('pre_count_users', function ($results, $strategy, $site_id) {
            $args = [
                // 'fields' => 'ID'
            ];

            $user_roles  = wp_get_current_user()->roles;
            $total_users = 1;
            $avail_roles = [];

            if (count(array_intersect($user_roles, ['administrator'])) > 0) {
                $args['role__not_in'] = [
                ];
            }

            if (count(array_intersect($user_roles, [
                'senior_administrator'
            ])) > 0) {
                $args['role__not_in'] = [
                    'administrator'
                ];
            }

            if (count(array_intersect($user_roles, [
                'junior_administrator'
            ])) > 0) {
                $args['role__not_in'] = [
                    'administrator',
                    // 'senior_administrator',
                    // 'junior_administrator',
                ];
            }

            $users = (new WP_User_Query($args))->get_results();

            if (! empty($users)) {
                $total_users = 0;

                foreach ($users as $user) {
                    foreach ($user->roles as $role) {
                        $avail_roles[$role] = ($avail_roles[$role] ?? 0) + 1;
                    }

                    if (empty($user->roles)) {
                        $avail_roles['none'] = ($avail_roles['none'] ?? 0) + 1;
                    }

                    $total_users++;
                }
            }

            return [
                'total_users' => $total_users,
                'avail_roles' => $avail_roles,
            ];
        }, 10, 3);

        add_filter('editable_roles', function ($roles) {
            $ed_roles   = $roles;
            $user_roles = wp_get_current_user()->roles;

            if (count(array_intersect($user_roles, ['administrator'])) > 0) {
            }

            if (count(array_intersect($user_roles, [
                'senior_administrator'
            ])) > 0) {
                unset($ed_roles['administrator']);
            }

            if (count(array_intersect($user_roles, [
                'junior_administrator'
            ])) > 0) {
                unset($ed_roles['administrator']);
                unset($ed_roles['senior_administrator']);
                unset($ed_roles['junior_administrator']);
            }

            if (count(array_intersect($user_roles, [
                'administrator',
                'senior_administrator',
                'junior_administrator',
            ])) < 1) {
                $ed_roles = [];
            }

            return $ed_roles;
        });

        // add_action('submit_application', function (array $details = []) {
        //     $cnt = [
        //         'jobs' => [],

        //         'edu'  => [
        //             'masters'  => 'Masters',
        //             'bachelor' => 'Bachelor degree',
        //             'ssce'     => 'Senior Secondary School certificate',
        //             'jsce'     => 'Junior Secondary School certificate',
        //             'primary'  => 'First School Leaving certificate',
        //         ],

        //         'know' => [
        //             'word_of_mouth' => 'Word of mouth',
        //             'google'        => 'Google',
        //             'facebook'      => 'Facebook',
        //             'instagram'     => 'Instagram',
        //             'x'             => 'X',
        //             'tiktok'        => 'Tiktok',
        //             'other'         => 'Other',
        //         ],
        //     ];

        //     foreach (((new WP_Query([
        //         'post_type'           => 'job_position',
        //         'post_status'         => 'publish',
        //         'has_password'        => false,
        //         'ignore_sticky_posts' => false,
        //         'order'               => 'DESC',
        //         'orderby'             => 'date',
        //         'nopaging'            => true,
        //         'paged'               => false,
        //     ]))->posts) as $_) {
        //         $cnt['jobs'][$_->ID] = $_->post_title;
        //     }

        //     $check = array_key_exists($details['job'], $cnt['jobs']);
        //     $check = $check && (! empty($details['name']) && (strlen($details['name']) <= 100));
        //     $check = $check && (! empty($details['email']) && (strlen($details['email']) <= 80));
        //     $check = $check && ((strlen($details['phone']) === 11) && ctype_digit($details['phone']));
        //     $check = $check && (array_key_exists($details['edu'], $cnt['edu']));
        //     $check = $check && (! empty($details['skills']) && (strlen($details['skills']) <= 500));
        //     $check = $check && (! empty($details['about']) && (strlen($details['about']) <= 500));
        //     $check = $check && (is_string($details['cv']) && ! empty((string) $details['cv']));

        //     if (isset($details['letter'])) {
        //         $check = $check && (is_string($details['letter']) && ! empty((string) $details['letter']));
        //     }

        //     $check = $check && (array_key_exists($details['know'], $cnt['know']));

        //     $check = $check && empty((new WP_Query([
        //         'post_type'    => 'job_application',
        //         'nopaging'     => true,
        //         'meta_query' => [
        //             [
        //                 'key'     => 'email',
        //                 'value'   => $details['email'],
        //                 'compare' => '=',
        //             ],
        //         ],
        //     ]))->posts);

        //     if (! $check) {
        //         return;
        //     }

        //     $ref = strtoupper(base_convert(((string) random_int(1, 9))
        //     . ((string) Clock::now())
        //     . ((string) random_int(0, 999))
        //     . ((string) random_int(0, 999)), 10, 36));

        //     wp_insert_post([
        //         'post_title'     => $cnt['jobs'][$details['job']] . ' - ' . $ref,
        //         'post_content'   => '',
        //         'post_type'      => 'job_application',
        //         'post_status'    => 'publish',
        //         'comment_status' => 'closed',
        //         'ping_status'    => 'closed',
        //         'meta_input'     => [
        //             'job'         => $details['job'],
        //             'name'        => $details['name'],
        //             'email'       => $details['email'],
        //             'phone'       => $details['phone'],
        //             'edu'         => $details['edu'],
        //             'skills'      => $details['skills'],
        //             'about'       => $details['about'],
        //             'cv'          => $details['cv'],
        //             'letter'      => $details['letter'] ?? '',
        //             'know'        => $details['know'],
        //             'ref'         => $ref,
        //         ],
        //     ], true, true);

        // }, 10, 1);

        // add_action('save_post_job_application', function ($post_id, $post, $update) {
        //     // $student          = get_post_meta($post_id, 'sender', true);
        //     // $course           = get_post_meta($post_id, 'item', true);
        //     // $payment_status   = get_post_meta($post_id, 'status', true);
        //     // var_dump('saved', $post_id, $post, $update);exit;
        //     // wp_schedule_single_event(Clock::now() + );
        // }, 10, 3);

        add_filter('cron_schedules', function ($schedules) {
            return array_merge($schedules, [
                'one_minute' => [
                    'interval' => 60,
                    'display'  => 'Once Every Minute',
                ],

                'five_minutes' => [
                    'interval' => 60 * 5,
                    'display'  => 'Once Every 5 Minutes',
                ],

                'fifteen_minutes' => [
                    'interval' => 60 * 15,
                    'display'  => 'Once Every 15 Minutes',
                ],

                'half_hour' => [
                    'interval' => 60 * 30,
                    'display'  => 'Once Every 30 Minutes',
                ],
            ]);
        });

        /**
         * Register a custom menu page.
         */

        add_action( 'admin_menu', function () {
            add_menu_page(
                __( 'Control Panel', 'natokpe' ),
                'Control Panel',
                'manage_options',
                'control-panel',
                function () {
                    ?><div class="wrap">
                        <h2 style="margin-bottom: 1em;">Control Panel</h2>
                        <h3>Email</h3>
                        <ul class="wrap">
                            <li>
                                <a href="<?php echo admin_url('/admin.php?page=email-settings'); ?>">Email Settings</a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('/edit.php?post_type=mail_server'); ?>">Mail Servers</a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('/edit.php?post_type=email_account'); ?>">Email Accounts</a>
                            </li>
                            <li>
                                <a href="<?php echo admin_url('/edit.php?post_type=email_template'); ?>">Email Templates</a>
                            </li>
                        </ul>

                        <h3>Assets</h3>
                        <ul class="wrap">
                            <li>
                                <a href="<?php echo admin_url('/admin.php?page=manage-assets'); ?>">Manage Assets</a>
                            </li>
                        </ul>
                    </div><?php
                },
                'dashicons-editor-kitchensink',
                200
            );
        });
        
        add_action('wp_mail_succeeded', function ($mail_data) {
            // var_dump('email sent', $mail_data);
        }, 10, 1);

        add_action('wp_mail_failed', function ($mail_data) {
            // var_dump('email failed', $mail_data);
        }, 10, 1);

        add_action('email_verification', function ($recipient, WP_User $user) {
            $allow = self::get_option('email_ver_allow', false, 'email-sending-options');

            if (! (is_string($allow) ? (strtolower($allow) === 'on') : $allow === true)) {
                do_action('wp_mail_failed', new WP_Error( 'wp_mail_failed', 'Email sending disabled for Email Verification type', $att));
                return;
            }

            // generate verification code
            // generate verification url

            $mdata = [
                'verification_code' => '123456',
                'verification_url'  => home_url(),
                'user'              => [
                    'email' => $recipient,
                    'role'  => '',
                ],

                'site' => [
                    'name' => '',
                ],
            ];

            $mode = self::get_option('email_ver_mode', null, 'email-sending-options');

            if ($mode === 'instant') {
                $ac  = self::get_option('email_ver_ac', null, 'email-sending-options');
                $tpl = self::get_option('email_ver_tpl', null, 'email-sending-options');

                if (ctype_digit((string) $ac) && ctype_digit((string) $tpl)) {
                    $email = new Email((int) $ac, (int) $tpl);
                    $email->prepare($mdata);
                    $email->send($recipient);
                }
            } else {
            }

        }, 10, 2);

        add_filter( "pre_wp_mail", function ($return, $att) {
            $opt = self::get_option('allow_email', false, 'email-settings');
            $allow = false;

            if (is_string($opt) ? (strtolower($opt) === 'on') : $opt === true) {
                // Email sending is enabled
                $allow = null;
            } else {
                do_action('wp_mail_failed', new WP_Error( 'wp_mail_failed', 'Email sending disabled for all types', $att));
                $allow = false;
            }

            return $allow;
        }, 1, 2);
    }
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
