<?php
/**
 * テンプレートコンポーザー定義
 *
 * $Author: mizoguchi $
 * $Rev: 144 $
 * $Date: 2013-10-04 00:11:03 +0900 (2013/10/04 (金)) $
 */

function _composer_base($view) {

	// JSアセットの登録
    Asset::add('jquery',            'js/libs/jquery.js');
    Asset::add('jquery-migrate',    'js/libs/jquery-migrate.js',                        'jquery');
    Asset::add('jquery.cookie',     'js/libs/jquery.cookie.js',                         'jquery-migrate');
    Asset::add('jquery.bgiframe',   'js/libs/jquery.bgiframe.min.js',                   'jquery.cookie');
    Asset::add('jquery.easyui',     'js/libs/jquery.easyui/jquery.easyui.min.js',       'jquery.bgiframe');
    Asset::add('easyui.locale',     'js/libs/jquery.easyui/locale/easyui-lang-jp.js',   'jquery.easyui');
    
    Asset::add('messages',          'js/messages.js',                                   'jquery-migrate');
    Asset::add('definitions',       'js/definitions.js',                                'jquery-migrate');
    Asset::add('common',            'js/common.js',                                     'easyui.locale');
    
    
    // CSSアセットの登録
    Asset::add('easyui',            'js/libs/jquery.easyui/themes/default/easyui.css');
    Asset::add('easyui.icons',      'js/libs/jquery.easyui/themes/icon.css',            'easyui');
    Asset::add('forms',             'css/forms.css',                                    'easyui.icons');
    Asset::add('notifications',     'css/notifications.css',                            'forms');
    
}

View::composer('template_common', function ($view) {
	_composer_base($view);
});

View::composer('template_office', function ($view) {
	_composer_base($view);
});

View::composer('template_construction', function ($view) {
	_composer_base($view);
});

View::composer('template_parts', function ($view) {
	_composer_base($view);
});
