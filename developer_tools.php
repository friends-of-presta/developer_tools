<?php
/**
 * @author Mickaël Andrieu <andrieu.travail@gmail.com>
 *
 * This module register every available hook in PrestaShop application
 * and display a useful block to show where the hook is rendered.
 */
class Developer_Tools extends Module
{
    /**
     * @var array the full list of registered hooks.
     */
    private $hooks;

    public function __construct()
    {
        $this->name = 'developer_tools';
        $this->version = '1.0.0';
        $this->author = 'Mickaël Andrieu';

        parent::__construct();

        $this->displayName = 'Developer tools';
        $this->description = 'Help the developer creates modules and themes.';
        $this->ps_versions_compliancy = [
            'min' => '1.6.0.0',
            'max' => _PS_VERSION_,
        ];

        $sql = new DbQuery();
        $sql->select('name')->from('hook', 'h');

        $this->hooks = Db::getInstance()->executeS($sql);
    }

    /**
     * Module installation.
     *
     * @return bool Success of the installation
     */
    public function install()
    {
        return parent::install() && $this->registerHooks($this->hooks);
    }

    /**
     * Uninstall the module.
     *
     * @return bool Success of the uninstallation
     */
    public function uninstall()
    {
        return parent::uninstall() && $this->unregisterHooks($this->hooks);
    }

    /**
     * Helper function to register a list of hooks.
     *
     * @param array $hooks
     * @param null $shopList
     */
    private function registerHooks(array $hooks, $shopList = null)
    {
        foreach($hooks as $hook) {
            $this->registerHook($hook, $shopList);
        }
    }

    /**
     * Helper function to unregister a list of hooks.
     *
     * @param array $hooks
     * @param null $shopList
     */
    private function unregisterHooks(array $hooks, $shopList = null)
    {
        foreach($hooks as $hook) {
            $this->unregisterHook($hook, $shopList);
        }
    }

    public function hookDisplayHeader($arguments)
    {
        $this->context->controller->registerStylesheet(
            'developer-tools-style',
            'modules/'.$this->name.'/public/css/hook.css',
            [
                'media' => 'all',
                'priority' => 1000,
            ]
        );

        return $this->__call('actionFrontControllerSetMedia', $arguments);
    }

    public function hookActionAdminControllerSetMedia($arguments)
    {
        $this->context->controller->addCSS($this->_path.'public/css/hook.css');

        return $this->__call('actionAdminControllerSetMedia', $arguments);
    }

    public function __call($name, $arguments) {
        if ($name == 'hookDisplayOverrideTemplate') {
            return;
        }
        
        if (defined('PS_ADMIN_DIR') && Tools::version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
            dump($name, $arguments);
        }

        $this->context->smarty->assign('name', $name);
        return $this->display(__FILE__ , 'views/templates/hook.tpl');
    }
}
