<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

declare(strict_types = 1);

namespace NatOkpe\Wp\Plugin\KeekSetup;

class Config
{
    /**
     * @var array
     */
    private
    $_engine = [];

    /**
     * @var array
     */
    private
    $_meta = [];

    /**
     * @var array
     */
    private
    $_options = [];

    /**
     * @var array
     */
    private
    $_post_types = [];

    /**
     * @var array
     */
    private
    $_roles = [];

    /**
     * @var array
     */
    private
    $_tax = [];

    /**
     * @var bool
     */
    private
    $_closed = false;

    /**
     * 
     */
    public
    function __construct(array $config = [])
    {
        $conf = [
            'engine'           => Engine::dirConfig('engine.php'),
            'meta'           => Engine::dirConfig('meta.php'),
            'options'            => Engine::dirConfig('options.php'),
            'post_types'          => Engine::dirConfig('post_types.php'),
            'roles' => Engine::dirConfig('roles.php'),
            'tax' => Engine::dirConfig('tax.php'),
        ];

        foreach ($conf as $_ => $__) {
            $conf[$_] = file_exists($__) ? require($__) : [];

            switch ($_) {
                case 'engine':
                    $this->_engine = is_array($conf[$_]) ? $conf[$_] : [];
                    break;

                case 'meta':
                    if (! is_array($conf[$_])) {
                        break;
                    }

                    foreach ($conf[$_] as $_bid => $_b_params) {
                        if (! isset($_b_params['box-fields'])) {
                            break;
                        }

                        $box = ['id' => $_bid] + $_b_params;

                        foreach ($_b_params['box-fields'] as $_fid => $_f_params) {
                            $box['box-fields'][$_fid]['id'] = $_fid;

                            if ($_f_params['type'] === 'group') {
                                foreach ($_f_params['group-fields'] as $_gfid => $_gf_params) {
                                    $box['box-fields'][$_fid]['group-fields'][$_gfid]['id'] = $_gfid;
                                }
                            }
                        }

                        $this->_meta[$_bid] = $box;
                    }
                    break;

                case 'options':
                    if (! is_array($conf[$_])) {
                        break;
                    }

                    foreach ($conf[$_] as $_p => $__p) {
                        $page = [
                            'id' => $_p,
                            'object_types' => ['options-page']
                        ] + $__p;

                        foreach ($page['page-sections'] ?? [] as $_s => $__s) {
                            $section = [
                                'id' => $_s,
                                'type' => 'title',
                            ] + $__s;

                            foreach ($section['fields'] as $_f => $__f) {
                                $field = [
                                    'id' => $_f,
                                ] + $__f;

                                if ($field['type'] === 'group') {
                                    foreach ($field['group-fields'] as $_gf => $__gf) {
                                        $field['group-fields'][$_gf]['id'] = $_gf;
                                    }
                                }

                                $section['fields'][$_f] = $field;
                            }

                            $page['page-sections'][$_s] = $section;
                        }

                        $this->_options[$_p] = $page;
                    }
                    break;

                case 'post_types':
                    if (! is_array($conf[$_])) {
                        break;
                    }

                    foreach ($conf[$_] as $_pt_id => $_pt_params) {
                        $this->_post_types[$_pt_id] = $_pt_params;
                    }
                    break;

                case 'roles':
                    if (! is_array($conf[$_])) {
                        break;
                    }

                    foreach ($conf[$_] as $_role_id => $_role_params) {
                        $this->_roles[$_role_id] = $_role_params;
                    }
                    break;

                case 'tax':
                    if (! is_array($conf[$_])) {
                        break;
                    }

                    foreach ($conf[$_] as $_tax_id => $_tax_params) {
                        $this->_tax[$_tax_id] = [
                            'object_type' => $_tax_params['object_type'] ?? [],
                            'args'        => $_tax_params['args'] ?? [],
                        ];
                    }
                    break;

            }
        }

    }

    /**
     * 
     */
    public
    function config(array $config = [])
    {
    }

    /**
     * 
     */
    public
    function __set(string $name, $value = null)
    {
        if ($this->_closed) {
            trigger_error('Config can no longer be modified', E_USER_NOTICE);
        }

        switch ($name) {
            default:
                $trace = debug_backtrace();
                $msg   = 'Undefined property: '
                . $name . ' in '
                . $trace[0]['file']
                . ' on line ' . $trace[0]['line'];

                trigger_error($msg, E_USER_NOTICE);

                return null;
        }
    }

    /**
     * 
     */
    public
    function __get(string $name)
    {
        switch ($name) {
            case "root":
                return get_stylesheet_directory();
                break;

            default:
                if (isset($this->{'_' . $name})) {
                    return $this->{'_' . $name};
                }

                $trace = debug_backtrace();
                $msg   = 'Undefined property: '
                . $name . ' in '
                . $trace[0]['file']
                . ' on line ' . $trace[0]['line'];

                trigger_error($msg, E_USER_NOTICE);

                return null;
        }
    }

    /**
     * 
     */
    public static
    function __callStatic(string $name, array $args = [])
    {
        switch ($name) {
            case "get":
                $config = new self([]);

                return $config->{'_' . ($args[0] ?? '')};
                break;

            default:
                $trace = debug_backtrace();
                $msg   = 'Undefined static method: '
                . $name . ' in '
                . $trace[0]['file']
                . ' on line ' . $trace[0]['line'];

                trigger_error($msg, E_USER_NOTICE);

                return null;
        }
    }

    /**
     * 
     */
    public
    function close(): self
    {
        $this->_closed = true;
        return $this;
    }
}
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
