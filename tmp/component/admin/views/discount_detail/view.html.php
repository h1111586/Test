<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/category.php';

class discount_detailVIEWdiscount_detail extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT_DETAIL'), 'redshop_discountmanagmenet48');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$layout = JRequest::getVar('layout');

		if ($layout == 'product')
		{
			$this->setLayout('product');

			$isNew = ($detail->discount_product_id < 1);
		}
		else
		{
			$isNew = ($detail->discount_id < 1);
		}

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_discountmanagmenet48');
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$model = $this->getModel('discount_detail');

		$selectedShoppers = $model->selectedShoppers();

		$shoppers = $this->get('shoppers');

		$lists['shopper_group_id'] = JHTML::_('select.genericlist', $shoppers, 'shopper_group_id[]',
			'class="inputbox" multiple="multiple" size="10"', 'value', 'text', $selectedShoppers
		);

		$discount_type = array(JHTML::_('select.option', 'no', JText::_('COM_REDSHOP_SELECT')),
			JHTML::_('select.option', 0, JText::_('COM_REDSHOP_TOTAL')),
			JHTML::_('select.option', 1, JText::_('COM_REDSHOP_PERCENTAGE'))
		);

		$lists['discount_type'] = JHTML::_('select.genericlist', $discount_type, 'discount_type',
			'class="inputbox" size="1"', 'value', 'text', $detail->discount_type
		);

		if (isset($detail->category_ids) === true)
		{
			$detail->category_ids = explode(',', $detail->category_ids);
		}
		else
		{
			$detail->category_ids = array();
		}

		$product_category = new product_category;
		$lists['category_ids'] = $product_category->list_all("category_ids[]", 0, $detail->category_ids, 10, false, true);

		$discount_condition = array(
			JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT')),
			JHTML::_('select.option', 1, JText::_('COM_REDSHOP_LOWER')),
			JHTML::_('select.option', 2, JText::_('COM_REDSHOP_EQUAL')),
			JHTML::_('select.option', 3, JText::_('COM_REDSHOP_HIGHER'))
		);

		$lists['discount_condition'] = JHTML::_('select.genericlist', $discount_condition, 'condition',
			'class="inputbox" size="1"', 'value', 'text', $detail->condition
		);

		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $detail->published);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
