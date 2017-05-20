<?php

/**
 * Тест для класса Core_Dump.
 */
class Core_ObserverTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * Настройка.
	 *
	 * @return void
	 */
	public function setUp()
	{
		// Инициализируем базовые константы для работы HostCMS
		Testing_Bootstrap::defineConstants();

		// Кастомный конфиг для БД
		Testing_Core_Config::setCustomConfig(array(
			'core_database' => array(
				'default' => array (
					'driver' => 'pdo',
					'host' => 'localhost',
					'username' => 'hostcms',
					'password' => 'hostcms',
					'database' => 'hostcms'
				)
			))
		);

		// Инциализируем ядро
		Testing_Core::init();
	}

	/**
	 * Тестирование наблюдателя.
	 *
	 * @return void
	 */
	public function testObserver()
	{
		$oSite = Core_Entity::factory('Site');
		$oSite->name = 'test site';
		$oSite->admin_email = 'test@example.com';
		$oSite->save();

		$oShop = Core_Entity::factory('Shop');
		$oShop->name = 'test shop';
		$oShop->email = 'test@example.com';
		$oShop->add($oSite);

		$oShopItem = Core_Entity::factory('Shop_Item');
		$oShopItem->datetime = Core_Date::timestamp2sql(date('now'));
		$oShopItem->start_datetime = Core_Date::timestamp2sql(date('now'));
		$oShopItem->end_datetime = Core_Date::timestamp2sql(date('now'));
		$oShopItem->add($oShop);

		$modificationsCount = $oShopItem->Modifications->getCount();
		$modificationTag = "<modifications_count>{$modificationsCount}</modifications_count>";

		// Без события
		$this->assertTrue(strpos($oShopItem->getXml(), $modificationTag) === FALSE);

		// Прикрепляем событие
		Shop_Item_Observer_AttachHashedId::attach();
		$this->assertTrue(strpos($oShopItem->getXml(), $modificationTag) !== FALSE);

		// Открепляем событие
		Shop_Item_Observer_AttachHashedId::detach();
		$this->assertTrue(strpos($oShopItem->getXml(), $modificationTag) === FALSE);

		// Тестируем вывод события
		Shop_Item_Observer_AttachHashedId::attach()->verbose = TRUE;

		ob_start();
		$oShopItem->getXml();
		$output = ob_get_clean();

		$this->assertSame('shop_item.onBeforeGetXml --> Shop_Item_Observer_AttachHashedId.onBeforeGetXml', $output);

		$oSite->delete();
	}
}