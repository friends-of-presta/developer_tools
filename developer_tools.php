<?php
/**
 * @author Mickaël Andrieu <andrieu.travail@gmail.com>
 *
 * This module provides tools for PrestaShop developers
 */

use PrestaShopBundle\Form\Admin\Type\SwitchType;

require_once 'vendor/autoload.php';

class Developer_Tools extends Module
{
    const HOOKS_DISPLAY = 'DEV_TOOLS_HOOKS_DISPLAY';
    const PROFILER = 'DEV_TOOLS_PROFILER';

    /**
     * @var array the full list of registered hooks.
     */
    private $hooks;

    /**
     * In constructor we define our module's meta data.
     * It's better tot keep constructor (and main module class itself) as thin as possible
     * and do any processing in controller.
     */
    public function __construct()
    {
        $this->name = 'developer_tools';
        $this->version = '2.0.0';
        $this->author = 'Mickaël Andrieu';

        $this->displayName = 'Developer tools';
        $this->description = 'Help the developer creates modules and themes.';
        $this->ps_versions_compliancy = [
            'min' => '1.7.5.0',
            'max' => _PS_VERSION_,
        ];

        $sql = new DbQuery();
        $sql->select('name')->from('hook', 'h');

        $this->hooks = Db::getInstance()->executeS($sql);

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        Configuration::updateValue(self::HOOKS_DISPLAY, false);
        Configuration::updateValue(self::PROFILER, false);

        $installStatus = parent::install() &&
            $this->registerHook('actionPerformancePageForm') &&
            $this->registerHook('actionPerformancePageSave')
        ;

        $this->registerHooks($this->hooks);

        return $installStatus;

    }

    /**
     * {@inheritdoc}
     */
    public function uninstall()
    {
        Configuration::deleteByName(self::HOOKS_DISPLAY);
        Configuration::deleteByName(self::PROFILER);

        return parent::uninstall();
    }

    /**
     * Helper function to register a list of hooks.
     *
     * @param array $hooks
     */
    private function registerHooks(array $hooks)
    {
        foreach($hooks as $hook) {
            $this->registerHook($hook);
        }
    }

    /**
     * Add the required styles and javascript for the Hook Display.
     *
     * @param $arguments
     * @return string
     */
    public function hookDisplayHeader($arguments)
    {
        if ($this->isHooksDisplayEnabled()) {
            $this->context->controller->registerStylesheet(
                'developer-tools-style',
                'modules/'.$this->name.'/public/css/hook.css',
                [
                    'media' => 'all',
                    'priority' => 1000,
                ]
            );
            $this->context->controller->registerJavascript(
                'developer-tools-javascript',
                'modules/'.$this->name.'/public/js/hook.js',
                [
                    'position' => 'bottom',
                    'priority' => 1000,
                ]
            );
        }

        return $this->__call('actionFrontControllerSetMedia', $arguments);
    }

    /**
     * Enables the displaying of every hook of display.
     *
     * @param array $arguments
     * @return string
     */
    public function hookActionAdminControllerSetMedia($arguments)
    {
        $this->context->controller->addCSS($this->_path.'public/css/hook.css');
        $this->context->controller->addJS($this->_path.'public/js/hook.js');

        return $this->__call('actionAdminControllerSetMedia', $arguments);
    }

    /**
     * Display the new options under Performances Page
     */
    public function hookActionPerformancePageForm(&$hookParams)
    {
        $formBuilder = $hookParams['form_builder'];
        $optionalFeatures = $formBuilder->get('optional_features');
        $optionalFeatures->add(
            'hooks_display',
            SwitchType::class,
            [
                'label' => 'Display available hooks?',
                'data' => $this->isHooksDisplayEnabled(),
            ]
        );

        $optionalFeatures->add(
            'profiler',
            SwitchType::class,
            [
                'label' => 'Display the Front Profiler',
                'data' => $this->isProfilerEnabled(),
            ]
        );
    }

    public function hookActionPerformancePageSave($hookParams)
    {
        $hooksDisplayFeatureEnabled = $hookParams['form_data']['optional_features']['hooks_display'];
        $profilerFeatureEnabled = $hookParams['form_data']['optional_features']['profiler'];

        Configuration::updateValue(self::HOOKS_DISPLAY, (bool) $hooksDisplayFeatureEnabled);
        Configuration::updateValue(self::PROFILER, (bool) $profilerFeatureEnabled);
    }

    /**
     * @param string $hookName
     * @param array $hookArguments
     * @return string
     */
    public function __call($hookName, $hookArguments) {
        if ($hookName === 'hookDisplayOverrideTemplate' || strpos($hookName, 'filter') !== false) {
            return '';
        }

        if ($this->isHooksDisplayEnabled()) {
            $this->context->smarty->assign('name', $hookName);

            return $this->display(__FILE__ , 'views/hook.tpl');
        }
    }

    private function isHooksDisplayEnabled()
    {
        return (bool) Configuration::get(self::HOOKS_DISPLAY);
    }

    private function isProfilerEnabled()
    {
        return (bool) Configuration::get(self::PROFILER);
    }
}
