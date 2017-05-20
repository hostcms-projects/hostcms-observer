<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Прикрепляет количество модификаций товара.
 */
class Shop_Item_Observer_AttachHashedId extends Core_Observer
{
	/**
	 * Массив событий для обработки.
	 *
	 * @var array
	 */
	public $aEvents = array(
		'shop_item.onBeforeGetXml'
	);

	/**
	 * Выводить сообщение при срабатывании события.
	 *
	 * @var boolean
	 */
	public $verbose = FALSE;

	/**
	 * Тег.
	 *
	 * @var string
	 */
	protected $_tag = 'modifications_count';

	/**
	 * Прикрепляет количество модификаций товара.
	 *
	 * @param  Shop_Item_Model  $oShopItem
	 * @param  array  $aArguments
	 * @return void
	 */
	public function onBeforeGetXml(Shop_Item_Model $oShopItem, array $aArguments)
	{
		$oShopItem->addXmlTag($this->_tag, $oShopItem->Modifications->getCount());
	}
}